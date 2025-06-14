<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Helpers\DocumentNumberExtractor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ArsipController extends Controller
{
    public function index()
    {
        $arsips = Arsip::all();

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

    // Create the arsip
    $arsip = Arsip::create($data);

    // Calculate retention date (5 years from tanggal_arsip)
    $arsip->calculateRetentionDate();

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

        $oldTanggalArsip = $arsip->tanggal_arsip;
        $arsip->update($data);

        // Recalculate retention date if tanggal_arsip changed
        if ($oldTanggalArsip != $arsip->tanggal_arsip) {
            $arsip->calculateRetentionDate();
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
        if ($arsip->file_path) {
            return Storage::disk('public')->download($arsip->file_path);
        }

        return back()->with('error', 'File tidak ditemukan');
    }

    // Check for archives that need notification
    public function checkNotifications()
    {
        $count = 0;
        $arsips = Arsip::where('is_archived_to_jre', false)->get();

        foreach ($arsips as $arsip) {
            // Only mark as notification if current date is past or equal to retention date
            if (!$arsip->has_retention_notification && $arsip->retention_date && Carbon::now()->gte($arsip->retention_date)) {
                $arsip->has_retention_notification = true;
                $arsip->save();
                $count++;
            }
        }

        return redirect()->back()->with('success', "$count arsip telah ditandai sudah pada masa retensi");
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

        // Try to extract classification code for AR, KP, or RT formats
        if (preg_match('/(AR|KP|RT)[\.|\s|\-\/]?(\d{2})[\.|\s|\-\/]?(\d{2})?[\.|\s|\-\/]?(\d{2})?/i', $documentNumber, $matches)) {
            $prefix = strtoupper($matches[1]); // AR, KP, or RT
            $mainCode = isset($matches[2]) ? $matches[2] : null;
            $subCode1 = isset($matches[3]) ? $matches[3] : null;
            $subCode2 = isset($matches[4]) ? $matches[4] : null;

            if ($mainCode) {
                // We have at least the main code (e.g., AR.01, KP.02, RT.03)
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
                }

                if ($subCode1) {
                    // We have the first subdivision (e.g., AR.01.01, KP.02.03, RT.03.01)
                    $code .= ".$subCode1";

                    if ($subCode2) {
                        // We have the second subdivision (e.g., AR.01.01.01)
                        $code .= ".$subCode2";
                    }
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
