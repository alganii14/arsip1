<?php

namespace App\Http\Controllers;

use App\Models\Jre;
use App\Models\Arsip;
use App\Models\ArchiveDestruction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class JreController extends Controller
{
    public function index(Request $request)
    {
        // Filter out destroyed archives from JRE list
        $query = Jre::with('arsip')->active();

        // Handle search
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->whereHas('arsip', function($q) use ($searchTerm) {
                $q->where('kode', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('nama_dokumen', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('rak', 'LIKE', "%{$searchTerm}%");
            });
        }

        $jres = $query->latest()->get();
        return view('jre.index', compact('jres'));
    }

    public function create()
    {
        // Get arsips that are not already in JRE and not destroyed
        $arsips = Arsip::where('is_archived_to_jre', false)->get();
        return view('jre.create', compact('arsips'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'arsip_id' => 'required|exists:arsips,id',
            'notes' => 'nullable|string',
        ]);

        $arsip = Arsip::findOrFail($request->arsip_id);

        // Move to JRE
        $arsip->moveToJre($request->notes);

        return redirect()->route('jre.index')->with('success', 'Arsip berhasil dipindahkan ke JRE');
    }

    public function show(Jre $jre)
    {
        $jre->load('arsip');
        return view('jre.show', compact('jre'));
    }

    public function edit(Jre $jre)
    {
        return view('jre.edit', compact('jre'));
    }

    public function update(Request $request, Jre $jre)
    {
        $request->validate([
            'status' => 'required|in:inactive,destroyed,transferred,recovered',
            'notes' => 'nullable|string',
        ]);

        $jre->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('jre.index')->with('success', 'Data JRE berhasil diperbarui');
    }

    public function destroy(Jre $jre)
    {
        // If we delete the JRE record, we should update the arsip record
        $arsip = $jre->arsip;

        // Only restore arsip if it's still marked as archived to JRE and not destroyed
        if ($arsip->is_archived_to_jre && $jre->status !== 'destroyed') {
            $arsip->is_archived_to_jre = false;
            $arsip->archived_to_jre_at = null;
            $arsip->save();
        }

        $jre->delete();

        return redirect()->route('jre.index')->with('success', 'Data JRE berhasil dihapus');
    }

    // Check for archives that need to be moved to JRE
    public function checkRetention()
    {
        $arsipsToMove = Arsip::where('is_archived_to_jre', false)
            ->whereNotNull('retention_date')
            ->whereDate('retention_date', '<=', Carbon::now())
            ->get();

        $count = 0;
        foreach ($arsipsToMove as $arsip) {
            // Mark retention notification first
            $arsip->has_retention_notification = true;
            $arsip->save();

            // Then automatically move to JRE
            $arsip->moveToJre();
            $count++;
        }

        return redirect()->back()->with('success', "$count arsip telah otomatis dipindahkan ke JRE");
    }

    // Recover archive from JRE
    public function recover(Jre $jre)
    {
        // Use the new method from JRE model
        $arsip = $jre->recoverToArsip();

        return redirect()->route('jre.index')->with('success', 'Arsip berhasil dipulihkan dari JRE dan masa retensi diperpanjang ' . $arsip->retention_years . ' tahun');
    }

    // Recover archive from JRE with manual years selection
    public function recoverWithYears(Request $request, Jre $jre)
    {
        $request->validate([
            'recovery_years' => 'required|string',
        ]);

        // Use the new method from JRE model
        $arsip = $jre->recoverWithCustomYears($request->recovery_years);

        $successMessage = $request->recovery_years === 'permanent'
            ? 'Arsip berhasil dipulihkan dari JRE dengan masa retensi permanen'
            : 'Arsip berhasil dipulihkan dari JRE dan masa retensi diperpanjang ' . $arsip->retention_years . ' tahun';

        return redirect()->route('jre.index')->with('success', $successMessage);
    }

    // Destroy archive
    public function destroyArchive(Request $request, Jre $jre)
    {
        try {
            // Log request data for debugging
            \Log::info('Destroy Archive Request:', [
                'jre_id' => $jre->id,
                'arsip_id' => $jre->arsip_id,
                'request_data' => $request->all()
            ]);

            $request->validate([
                'destruction_notes' => 'required|string|min:10',
                'destruction_method' => 'required|string',
                'destruction_location' => 'nullable|string',
                'destruction_witnesses' => 'nullable|string',
            ]);

            // Create destruction record
            $destruction = ArchiveDestruction::create([
                'arsip_id' => $jre->arsip_id,
                'jre_id' => $jre->id,
                'user_id' => Auth::id(),
                'destruction_notes' => $request->destruction_notes,
                'destruction_method' => $request->destruction_method,
                'destruction_location' => $request->destruction_location,
                'destruction_witnesses' => $request->destruction_witnesses,
                'destroyed_at' => now(),
            ]);

            // Update JRE status
            $jre->update([
                'status' => 'destroyed',
                'notes' => $jre->notes . "\n[" . now()->format('Y-m-d H:i:s') . "] Arsip dimusnahkan oleh " . Auth::user()->name
            ]);

            \Log::info('Archive destroyed successfully', [
                'jre_id' => $jre->id,
                'destruction_id' => $destruction->id
            ]);

            return redirect()->route('jre.index')->with('success', 'Arsip berhasil dimusnahkan dan tercatat dalam riwayat pemusnahan');

        } catch (\Exception $e) {
            \Log::error('Error destroying archive:', [
                'error' => $e->getMessage(),
                'jre_id' => $jre->id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat memusnahkan arsip: ' . $e->getMessage());
        }
    }

    // Transfer archive
    public function transfer(Request $request, Jre $jre)
    {
        $request->validate([
            'transfer_location' => 'required|string',
            'transfer_notes' => 'nullable|string',
        ]);

        // Update JRE status
        $jre->update([
            'status' => 'transferred',
            'notes' => $jre->notes . "\n[" . now() . "] Arsip dipindahkan ke: " . $request->transfer_location .
                      ($request->transfer_notes ? "\nCatatan: " . $request->transfer_notes : "")
        ]);

        return redirect()->route('jre.index')->with('success', 'Status arsip berhasil diubah menjadi dipindahkan');
    }

    // Show destruction history - arsip yang sudah dimusnahkan
    public function destructions()
    {
        $destructions = ArchiveDestruction::with(['arsip', 'user'])
            ->orderBy('destroyed_at', 'desc')
            ->get();

        return view('jre.destructions', compact('destructions'));
    }
}
