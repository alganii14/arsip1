<?php

namespace App\Http\Controllers;

use App\Models\Jre;
use App\Models\Arsip;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JreController extends Controller
{
    public function index()
    {
        $jres = Jre::with('arsip')->latest()->get();
        return view('jre.index', compact('jres'));
    }

    public function create()
    {
        // Get arsips that are not already in JRE
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
        $arsip->is_archived_to_jre = false;
        $arsip->archived_to_jre_at = null;
        $arsip->save();

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
            $arsip->moveToJre('Automatically moved to JRE based on retention date');
            $count++;
        }

        return redirect()->back()->with('success', "$count arsip telah otomatis dipindahkan ke JRE");
    }

    // Recover archive from JRE
    public function recover(Jre $jre)
    {
        $arsip = $jre->arsip;

        // Update JRE status
        $jre->update([
            'status' => 'recovered',
            'notes' => $jre->notes . "\n[" . now() . "] Arsip dipulihkan dari JRE."
        ]);

        // Update arsip status
        $arsip->is_archived_to_jre = false;
        $arsip->archived_to_jre_at = null;
        $arsip->has_retention_notification = false;

        // Extend retention period by 5 more years from current date
        $arsip->retention_date = Carbon::now()->addYears(5);
        $arsip->save();

        return redirect()->route('jre.index')->with('success', 'Arsip berhasil dipulihkan dari JRE dan masa retensi diperpanjang 5 tahun');
    }

    // Destroy archive
    public function destroyArchive(Jre $jre)
    {
        // Update JRE status
        $jre->update([
            'status' => 'destroyed',
            'notes' => $jre->notes . "\n[" . now() . "] Arsip dimusnahkan."
        ]);

        return redirect()->route('jre.index')->with('success', 'Status arsip berhasil diubah menjadi dimusnahkan');
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
}
