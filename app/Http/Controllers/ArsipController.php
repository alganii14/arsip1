<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Helpers\DocumentNumberExtractor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ArsipController extends Controller
{
    public function index(Request $request)
    {
        // Base query - only show active arsips (not archived to JRE)
        $query = Arsip::active();

        // Handle search
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('kode', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('nama_dokumen', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('kategori', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('rak', 'LIKE', "%{$searchTerm}%");
            });
        }

        $arsips = $query->get();

        // Check for notifications
        foreach ($arsips as $arsip) {
            if ($arsip->shouldShowRetentionNotification()) {
                $arsip->has_retention_notification = true;
                $arsip->save();
            }
        }

        return view('arsip.index', compact('arsips'));
    }

    public function create()
    {
        return view('arsip.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'kode' => 'required',
        'nama_dokumen' => 'required',
        'kategori' => 'required',
        'tanggal_arsip' => 'required|date', // This allows any valid date
        'rak' => 'nullable|string',
        'retention_type' => 'required|in:auto,manual',
        'retention_years' => 'required_if:retention_type,manual|integer|min:1|max:50',
        'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,xlsx,xls,doc,docx|max:10240',
    ]);

    $data = $request->all();

    // Handle file upload
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('arsip_files', $fileName, 'public');

        $data['file_path'] = $filePath;
        $data['file_type'] = $file->getClientOriginalExtension();
    }

    // Set retention years based on type
    if ($request->retention_type === 'manual') {
        $data['retention_years'] = $request->retention_years;
    } else {
        $data['retention_years'] = 5; // Default auto retention
    }

    // Create the arsip
    $arsip = Arsip::create($data);

    // Calculate retention date based on retention type
    if ($request->retention_type === 'manual') {
        $arsip->calculateRetentionDate($request->retention_years);
    } else {
        $arsip->calculateRetentionDate(); // Default 5 years
    }

    return redirect()->route('arsip.index')->with('success', 'Data berhasil disimpan');
}

    public function edit(Arsip $arsip)
    {
        return view('arsip.update', compact('arsip'));
    }

    public function update(Request $request, Arsip $arsip)
    {
        $request->validate([
            'kode' => 'required',
            'nama_dokumen' => 'required',
            'kategori' => 'required',
            'tanggal_arsip' => 'required|date',
            'rak' => 'nullable|string',
            'retention_type' => 'required|in:auto,manual',
            'retention_years' => 'required_if:retention_type,manual|integer|min:1|max:50',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,xlsx,xls,doc,docx|max:10240',
        ]);

        $data = $request->all();

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($arsip->file_path) {
                Storage::disk('public')->delete($arsip->file_path);
            }

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('arsip_files', $fileName, 'public');

            $data['file_path'] = $filePath;
            $data['file_type'] = $file->getClientOriginalExtension();
        }

        // Set retention years based on type
        if ($request->retention_type === 'manual') {
            $data['retention_years'] = $request->retention_years;
        } else {
            $data['retention_years'] = 5; // Default auto retention
        }

        $oldTanggalArsip = $arsip->tanggal_arsip;
        $oldRetentionYears = $arsip->retention_years;

        $arsip->update($data);

        // Recalculate retention date if tanggal_arsip or retention_years changed
        if ($oldTanggalArsip != $arsip->tanggal_arsip || $oldRetentionYears != $arsip->retention_years) {
            if ($request->retention_type === 'manual') {
                $arsip->calculateRetentionDate($request->retention_years);
            } else {
                $arsip->calculateRetentionDate(); // Default 5 years
            }
        }

        return redirect()->route('arsip.index')->with('success', 'Data berhasil diperbarui');
    }

    public function destroy(Arsip $arsip)
    {
        // Delete file if exists
        if ($arsip->file_path) {
            Storage::disk('public')->delete($arsip->file_path);
        }

        $arsip->delete();
        return redirect()->route('arsip.index')->with('success', 'Data berhasil dihapus');
    }

    public function download(Arsip $arsip)
    {
        $user = Auth::user();

        // Check if user is trying to download via peminjaman
        if ($user && $user->role === 'peminjam') {
            // Check if user has approved peminjaman for this arsip
            $approvedPeminjaman = $arsip->peminjaman()
                ->where('peminjam_user_id', $user->id)
                ->where('confirmation_status', 'approved')
                ->whereIn('status', ['dipinjam', 'terlambat']) // Only active loans
                ->first();

            if (!$approvedPeminjaman) {
                return back()->with('error', 'Akses ditolak. Anda hanya dapat mengunduh file arsip yang telah disetujui untuk dipinjam.');
            }
        }

        if ($arsip->file_path && Storage::disk('public')->exists($arsip->file_path)) {
            $filePath = storage_path('app/public/' . $arsip->file_path);
            return response()->download($filePath);
        }

        return back()->with('error', 'File tidak ditemukan');
    }

    // Check for archives that need notification (now automatically moves to JRE)
    public function checkNotifications()
    {
        $count = 0;
        $arsips = Arsip::where('is_archived_to_jre', false)->get();

        foreach ($arsips as $arsip) {
            // Auto-move to JRE if retention date reached
            if ($arsip->shouldMoveToJre()) {
                $arsip->autoMoveToJreIfExpired();
                $count++;
            }
        }

        return redirect()->back()->with('success', "$count arsip telah otomatis dipindahkan ke JRE");
    }
    public function detail(Arsip $arsip)
    {
        // Periksa apakah arsip ada di JRE
        if ($arsip->is_archived_to_jre) {
            return redirect()->route('arsip.index')->with('error', 'Arsip ini telah dipindahkan ke JRE dan tidak dapat diakses.');
        }

        // Periksa apakah file ada
        if ($arsip->file_path && !Storage::disk('public')->exists($arsip->file_path)) {
            session()->flash('warning', 'File arsip tidak ditemukan di server. Silakan hubungi administrator.');
        }

        return view('arsip.detail', compact('arsip'));
    }

    /**
     * Extract document number and date from uploaded file
     */
    /**
     * Determine classification based on document number
     *
     * @param string $documentNumber
     * @return array
     */
    private function determineClassificationFromNumber($documentNumber)
    {
        // Default classification if we can't determine it
        $category = 'Dokumen Arsip';
        $type = 'AR';
        $code = null;

        // Try to extract classification code for AR, KP, RT, or KU formats
        // Pattern yang menangkap semua format 3-level dan 4-level
        if (preg_match('/(KU|KP|RT|AR)\.(\d{2})\.(\d{2})(?:\.(\d{2}))?/i', $documentNumber, $matches)) {
            $prefix = strtoupper($matches[1]); // KU, KP, RT, or AR
            $mainCode = isset($matches[2]) ? $matches[2] : null;
            $subCode1 = isset($matches[3]) ? $matches[3] : null;
            $subCode2 = isset($matches[4]) ? $matches[4] : null;

            if ($mainCode) {
                // We have at least the main code (e.g., AR.01, KP.02, RT.03, KU.01)
                $code = "$prefix.$mainCode";
                $type = $prefix;

                // Set category based on type
                switch ($prefix) {
                    case 'AR':
                        $category = 'Kearsipan';
                        break;
                    case 'KP':
                        $category = 'Kepegawaian';
                        break;
                    case 'RT':
                        $category = 'Kerumahtanggaan';
                        break;
                    case 'KU':
                        $category = 'Keuangan';
                        break;
                }

                if ($subCode1) {
                    // We have the first subdivision (e.g., AR.01.01, KP.02.03, RT.03.01, KU.01.02)
                    $testCode = "$prefix.$mainCode.$subCode1";

                    // Check if this code exists in the ClassificationFormatter
                    $testDescription = \App\Helpers\ClassificationFormatter::getDescription($testCode);
                    if (!empty($testDescription)) {
                        $code = $testCode;

                        if ($subCode2) {
                            // We have the second subdivision (e.g., AR.01.01.01, KU.01.02.01)
                            $testCode2 = "$prefix.$mainCode.$subCode1.$subCode2";
                            $testDescription2 = \App\Helpers\ClassificationFormatter::getDescription($testCode2);
                            if (!empty($testDescription2)) {
                                $code = $testCode2;
                            }
                        }
                    }
                    // If the subdivision doesn't exist, keep the main code (e.g., KU.03 instead of KU.03.07)
                }
            }
        }

        return [
            'category' => $category,
            'type' => $type,
            'code' => $code
        ];
    }

    public function extractDocumentNumber(Request $request)
    {
        Log::info('DocumentExtraction: Starting document data extraction request');

        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf,xlsx,xls,doc,docx|max:10240',
        ]);

        $documentNumber = null;
        $documentDate = null;
        $documentName = null;
        $classification = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            Log::info('DocumentExtraction: Processing file: ' . $file->getClientOriginalName());

            try {
                $extractedData = DocumentNumberExtractor::extractDocumentData($file);
                Log::info('DocumentExtraction: Raw extraction result: ' . json_encode($extractedData));

                if (!empty($extractedData['documentNumber'])) {
                    $documentNumber = DocumentNumberExtractor::formatDocumentNumber($extractedData['documentNumber']);
                    Log::info('DocumentExtraction: Formatted document number: ' . $documentNumber);

                    // Get classification based on document number
                    $classification = $this->determineClassificationFromNumber($documentNumber);
                }

                if (!empty($extractedData['documentDate'])) {
                    $documentDate = $extractedData['documentDate'];
                    Log::info('DocumentExtraction: Extracted document date: ' . $documentDate);
                }

                if (!empty($extractedData['documentName'])) {
                    $documentName = $extractedData['documentName'];
                    Log::info('DocumentExtraction: Extracted document name: ' . $documentName);
                }

            } catch (\Exception $e) {
                Log::error('DocumentExtraction: Error during extraction: ' . $e->getMessage());
            }
        } else {
            Log::warning('DocumentExtraction: No file provided for document data extraction');
        }

        // If we have a classification code, add the description
        if (isset($classification['code'])) {
            $classification['description'] = \App\Helpers\ClassificationFormatter::getDescription($classification['code']);
        }

        return response()->json([
            'success' => $documentNumber !== null || $documentDate !== null || $documentName !== null,
            'documentNumber' => $documentNumber,
            'documentDate' => $documentDate,
            'documentName' => $documentName,
            'classification' => $classification
        ]);
    }
}
