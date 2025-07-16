<?php

namespace App\Http\Controllers;

use App\Models\Pemindahan;
use App\Models\Arsip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PemindahanExport;
use Barryvdh\DomPDF\Facade\Pdf;

class PemindahanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pemindahan::query();

        // Load necessary relationships
        $query->with(['arsip', 'user', 'approver', 'completer']);

        // Apply search filter if provided
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('arsip', function ($q) use ($search) {
                    $q->where('nama_dokumen', 'like', "%{$search}%")
                      ->orWhere('kode', 'like', "%{$search}%")
                      ->orWhere('nomor_dokumen', 'like', "%{$search}%");
                })
                ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        $pemindahans = $query->orderBy('created_at', 'desc')
                           ->paginate(10)
                           ->withQueryString();

        return view('pemindahan.index', compact('pemindahans'));
    }

    public function create(Request $request)
    {
        $arsip = null;

        // Jika ada arsip_id dari parameter, load data arsip
        if ($request->has('arsip_id')) {
            $arsip = Arsip::find($request->arsip_id);

            // Check if arsip is already moved
            if ($arsip && $arsip->isAlreadyMoved()) {
                return redirect()->route('pemindahan.index')
                    ->with('error', 'Arsip ini sudah pernah dipindahkan sebelumnya. Silahkan pilih arsip lain.');
            }
        }

        return view('pemindahan.create', compact('arsip'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'arsip_id' => 'required|exists:arsips,id',
            'tingkat_perkembangan' => 'required|in:asli,copy,asli_dan_copy',
            'jumlah_folder' => 'required|integer|min:1|max:10',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        // Check if arsip is already moved
        $arsip = Arsip::find($validatedData['arsip_id']);
        if ($arsip->isAlreadyMoved()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Arsip ini sudah pernah dipindahkan sebelumnya.'], 422);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Arsip ini sudah pernah dipindahkan sebelumnya. Silahkan pilih arsip lain.');
        }

        try {
            // Begin database transaction
            DB::beginTransaction();

            // Langsung buat pemindahan dengan status completed
            $pemindahan = Pemindahan::create([
                'arsip_id' => $validatedData['arsip_id'],
                'user_id' => Auth::id(),
                'tingkat_perkembangan' => $validatedData['tingkat_perkembangan'],
                'jumlah_folder' => $validatedData['jumlah_folder'],
                'keterangan' => $validatedData['keterangan'],
                'status' => 'completed',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'completed_by' => Auth::id(),
                'completed_at' => now(),
                'catatan_admin' => $arsip->is_archived_to_jre ? 'Pemindahan dari JRE' : 'Pemindahan langsung dari arsip',
            ]);

            // Only handle JRE if the arsip is actually in JRE
            if ($arsip->is_archived_to_jre) {
                // Update arsip to mark it's no longer in JRE
                $arsip->update([
                    'is_archived_to_jre' => false,
                    'archived_to_jre_at' => null
                ]);

                // Find and update JRE record status to 'transferred'
                $jre = \App\Models\Jre::where('arsip_id', $arsip->id)->first();
                if ($jre) {
                    // Log info before updating
                    Log::info('Arsip dipindahkan dari JRE', [
                        'arsip_id' => $arsip->id,
                        'jre_id' => $jre->id,
                        'pemindahan_id' => $pemindahan->id,
                        'user_id' => Auth::id()
                    ]);

                    // Update the JRE status to "transferred" instead of deleting
                    try {
                        $jre->update([
                            'status' => 'transferred',
                            'transferred_at' => now(),
                            'transferred_by' => Auth::id(),
                            'transfer_notes' => $validatedData['keterangan'] ?? 'Dipindahkan ke pemindahan'
                        ]);

                        // Double-check that the record is updated
                        $updatedJre = \App\Models\Jre::find($jre->id);
                        if (!$updatedJre || $updatedJre->status !== 'transferred') {
                            throw new \Exception("Failed to update JRE record status to transferred");
                        }
                    } catch (\Exception $e) {
                        Log::error('Error updating JRE record status', [
                            'error' => $e->getMessage(),
                            'jre_id' => $jre->id,
                            'arsip_id' => $arsip->id
                        ]);
                        throw $e; // Rethrow to trigger rollback
                    }
                }
            } else {
                // Arsip is not in JRE, just log that it's a direct pemindahan
                Log::info('Pemindahan langsung arsip (tidak dari JRE)', [
                    'arsip_id' => $arsip->id,
                    'pemindahan_id' => $pemindahan->id,
                    'user_id' => Auth::id()
                ]);
            }

            // Commit transaction
            DB::commit();

            // Determine success message based on whether arsip was in JRE
            $wasInJre = $arsip->getOriginal('is_archived_to_jre'); // Get original value before update
            $successMessage = $wasInJre
                ? 'Arsip berhasil dipindahkan dan status JRE diubah menjadi "Dipindahkan".'
                : 'Arsip berhasil dipindahkan.';

            Log::info('Pemindahan arsip berhasil dibuat', [
                'pemindahan_id' => $pemindahan->id,
                'arsip_id' => $pemindahan->arsip_id,
                'was_in_jre' => $wasInJre,
                'user_id' => Auth::id()
            ]);

            // Handle response based on request type
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage,
                    'redirect' => $wasInJre ? route('jre.index') : route('pemindahan.index')
                ]);
            }

            // Check for explicit redirect parameter
            if ($request->has('redirect_to')) {
                return redirect($request->redirect_to)
                    ->with('success', $successMessage);
            }

            // Check if the request is coming from JRE edit page
            $referer = request()->headers->get('referer');
            $isFromJre = str_contains($referer ?? '', '/jre/');

            // If the request is from JRE edit page or arsip was in JRE, redirect to JRE index
            if ($isFromJre || $wasInJre) {
                return redirect()->route('jre.index')
                    ->with('success', $successMessage);
            }

            return redirect()->route('pemindahan.index')
                ->with('success', $successMessage);
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();

            Log::error('Error creating pemindahan record', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Terjadi kesalahan saat memindahkan arsip: ' . $e->getMessage()], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memindahkan arsip: ' . $e->getMessage());
        }
    }

    public function show(Pemindahan $pemindahan)
    {
        $pemindahan->load(['arsip', 'user']);
        return view('pemindahan.show', compact('pemindahan'));
    }

    public function edit(Pemindahan $pemindahan)
    {
        // Hanya bisa edit jika status masih pending
        if ($pemindahan->status !== 'pending') {
            return redirect()->route('pemindahan.index')
                ->with('error', 'Pemindahan yang sudah diproses tidak dapat diedit.');
        }

        return view('pemindahan.edit', compact('pemindahan'));
    }

    public function update(Request $request, Pemindahan $pemindahan)
    {
        // Hanya bisa update jika status masih pending
        if ($pemindahan->status !== 'pending') {
            return redirect()->route('pemindahan.index')
                ->with('error', 'Pemindahan yang sudah diproses tidak dapat diubah.');
        }

        $validatedData = $request->validate([
            'tingkat_perkembangan' => 'required|in:asli,copy,asli_dan_copy',
            'jumlah_folder' => 'required|integer|min:1|max:10',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $pemindahan->update($validatedData);

        return redirect()->route('pemindahan.index')
            ->with('success', 'Data pemindahan berhasil diperbarui.');
    }

    public function destroy(Pemindahan $pemindahan)
    {
        // Hanya bisa hapus jika status masih pending
        if ($pemindahan->status !== 'pending') {
            return redirect()->route('pemindahan.index')
                ->with('error', 'Pemindahan yang sudah diproses tidak dapat dihapus.');
        }

        $pemindahan->delete();

        return redirect()->route('pemindahan.index')
            ->with('success', 'Permintaan pemindahan berhasil dihapus.');
    }

    public function approve(Request $request, Pemindahan $pemindahan)
    {
        $request->validate([
            'catatan_admin' => 'nullable|string|max:1000',
        ]);

        $pemindahan->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'catatan_admin' => $request->catatan_admin,
        ]);

        Log::info('Pemindahan arsip disetujui', [
            'pemindahan_id' => $pemindahan->id,
            'approved_by' => Auth::id()
        ]);

        return redirect()->route('pemindahan.index')
            ->with('success', 'Permintaan pemindahan telah disetujui.');
    }

    public function reject(Request $request, Pemindahan $pemindahan)
    {
        $request->validate([
            'catatan_admin' => 'required|string|max:1000',
        ]);

        $pemindahan->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'catatan_admin' => $request->catatan_admin,
        ]);

        Log::info('Pemindahan arsip ditolak', [
            'pemindahan_id' => $pemindahan->id,
            'rejected_by' => Auth::id()
        ]);

        return redirect()->route('pemindahan.index')
            ->with('success', 'Permintaan pemindahan telah ditolak.');
    }

    public function complete(Request $request, Pemindahan $pemindahan)
    {
        if ($pemindahan->status !== 'approved') {
            return redirect()->route('pemindahan.index')
                ->with('error', 'Hanya pemindahan yang disetujui yang dapat diselesaikan.');
        }

        $request->validate([
            'catatan_penyelesaian' => 'nullable|string|max:1000',
        ]);

        $pemindahan->update([
            'status' => 'completed',
            'completed_by' => Auth::id(),
            'completed_at' => now(),
            'catatan_penyelesaian' => $request->catatan_penyelesaian,
        ]);

        Log::info('Pemindahan arsip diselesaikan', [
            'pemindahan_id' => $pemindahan->id,
            'completed_by' => Auth::id()
        ]);

        return redirect()->route('pemindahan.index')
            ->with('success', 'Pemindahan arsip telah diselesaikan.');
    }

    public function getArsipData(Request $request)
    {
        try {
            $arsipId = $request->get('arsip_id');

            if (!$arsipId) {
                return response()->json(['error' => 'ID arsip tidak ditemukan'], 400);
            }

            $arsip = Arsip::with('creator')->find($arsipId);

            // Check if arsip is already moved
            if ($arsip && $arsip->isAlreadyMoved()) {
                return response()->json(['error' => 'Arsip ini sudah pernah dipindahkan sebelumnya.'], 400);
            }

            if (!$arsip) {
                return response()->json(['error' => 'Arsip tidak ditemukan'], 404);
            }

            // Format tanggal jika ada
            $tanggalArsip = '';
            if ($arsip->tanggal_arsip) {
                try {
                    $tanggalArsip = $arsip->tanggal_arsip->format('d M Y');
                } catch (\Exception $e) {
                    $tanggalArsip = $arsip->tanggal_arsip;
                }
            }

            $data = [
                'kode_arsip' => $arsip->kode ?? '',
                'nama_dokumen' => $arsip->nama_dokumen ?? '',
                'kategori' => $arsip->kategori ?? '',
                'dibuat_oleh' => $arsip->creator ? $arsip->creator->name : 'N/A',
                'tanggal_arsip' => $tanggalArsip,
                'keterangan' => $arsip->keterangan ?? '',
            ];

            return response()->json($data, 200, ['Content-Type' => 'application/json']);

        } catch (\Exception $e) {
            \Log::error('Error in getArsipData: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan server: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Export data pemindahan arsip ke Excel
     */
    public function exportExcel(Request $request)
    {
        $query = Pemindahan::query();

        // Load necessary relationships
        $query->with(['arsip', 'user', 'approver', 'completer']);

        // Apply search filter if provided
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('arsip', function ($q) use ($search) {
                    $q->where('nama_dokumen', 'like', "%{$search}%")
                      ->orWhere('kode', 'like', "%{$search}%")
                      ->orWhere('nomor_dokumen', 'like', "%{$search}%");
                })
                ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        $pemindahans = $query->orderBy('created_at', 'desc')->get();

        $fileName = 'laporan_pemindahan_arsip_' . now()->format('YmdHis') . '.xlsx';

        return Excel::download(
            new PemindahanExport($pemindahans, $request->search),
            $fileName
        );
    }

    /**
     * Export data pemindahan arsip ke PDF
     */
    public function exportPdf(Request $request)
    {
        $query = Pemindahan::query();

        // Load necessary relationships
        $query->with(['arsip', 'user', 'approver', 'completer']);

        // Apply search filter if provided
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('arsip', function ($q) use ($search) {
                    $q->where('nama_dokumen', 'like', "%{$search}%")
                      ->orWhere('kode', 'like', "%{$search}%")
                      ->orWhere('nomor_dokumen', 'like', "%{$search}%");
                })
                ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        $pemindahans = $query->orderBy('created_at', 'desc')->get();

        $pdf = PDF::loadView('exports.pemindahan-pdf', [
            'pemindahans' => $pemindahans,
            'search' => $request->search
        ]);

        $fileName = 'laporan_pemindahan_arsip_' . now()->format('YmdHis') . '.pdf';

        return $pdf->download($fileName);
    }

    /**
     * Download surat pemindahan arsip
     */
    public function downloadSurat(Request $request, Pemindahan $pemindahan)
    {
        // Pastikan arsip sudah dipindahkan (status completed)
        if ($pemindahan->status !== 'completed') {
            return redirect()->back()->with('error', 'Surat hanya dapat diunduh untuk pemindahan yang sudah selesai.');
        }

        // Validasi data form
        $validated = $request->validate([
            'nama_pihak_pertama' => 'required|string|max:255',
            'nip_pihak_pertama' => 'required|string|max:255',
            'jabatan_pihak_pertama' => 'required|string|max:255',
            'nama_pihak_kedua' => 'required|string|max:255',
            'nip_pihak_kedua' => 'required|string|max:255',
            'jabatan_pihak_kedua' => 'required|string|max:255',
        ]);

        try {
            $pemindahan->load(['arsip', 'user', 'completer']);

            // Data untuk surat
            $data = [
                'pemindahan' => $pemindahan,
                'tanggal_surat' => now(),
                'nomor_surat' => $this->generateNomorSurat($pemindahan->arsip->kode ?? $pemindahan->arsip->nomor_dokumen),
                'pihak_pertama' => [
                    'nama' => $validated['nama_pihak_pertama'],
                    'nip' => $validated['nip_pihak_pertama'],
                    'jabatan' => $validated['jabatan_pihak_pertama'],
                ],
                'pihak_kedua' => [
                    'nama' => $validated['nama_pihak_kedua'],
                    'nip' => $validated['nip_pihak_kedua'],
                    'jabatan' => $validated['jabatan_pihak_kedua'],
                ],
            ];

            $pdf = PDF::loadView('surat.pemindahan-arsip', $data);
            $pdf->setPaper('A4', 'portrait');

            // Clean filename dari karakter yang tidak valid
            $kodeArsip = $pemindahan->arsip->kode ?? $pemindahan->arsip->nomor_dokumen ?? 'ARSIP';
            $cleanKode = preg_replace('/[\/\\\\:*?"<>|]/', '_', $kodeArsip);
            
            $fileName = 'Surat_Pemindahan_' . $cleanKode . '_' . now()->format('YmdHis') . '.pdf';

            // Set headers untuk memaksa download
            return response($pdf->output(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"')
                ->header('Cache-Control', 'private, max-age=0, must-revalidate')
                ->header('Pragma', 'public');
                
        } catch (\Exception $e) {
            \Log::error('Error generating PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat surat. Silakan coba lagi.');
        }
    }

    /**
     * Generate nomor surat berdasarkan kode arsip
     */
    private function generateNomorSurat($kodeArsip)
    {
        $tanggal = now();
        $bulan = str_pad($tanggal->month, 2, '0', STR_PAD_LEFT);
        $tahun = $tanggal->year;
        
        // Format: 001/KODE-ARSIP/KECAMATAN/MM/YYYY
        return sprintf('001/%s/KECAMATAN/%s/%s', $kodeArsip, $bulan, $tahun);
    }
}
