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
                  ->orWhere('nama_dokumen', 'LIKE', "%{$searchTerm}%");
            });
        }

        $jres = $query->latest()->get();
        return view('jre.index', compact('jres'));
    }

    public function create()
    {
        // Get arsips that are not already in JRE and not destroyed
        $arsips = Arsip::whereDoesntHave('jre')
                      ->where('is_archived_to_jre', false)
                      ->get();

        return view('jre.create', compact('arsips'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'arsip_id' => 'required|exists:arsips,id',
            'notes' => 'nullable|string'
        ]);

        $arsip = Arsip::findOrFail($request->arsip_id);

        // Use the moveToJre method which now handles duplicates
        $jre = $arsip->moveToJre($request->notes);

        return redirect()->route('jre.index')->with('success', 'Arsip berhasil dipindahkan ke JRE');
    }

    public function show(Jre $jre)
    {
        $jre->load('arsip');
        return view('jre.show', compact('jre'));
    }

    public function edit(Jre $jre)
    {
        $jre->load('arsip');
        return view('jre.edit', compact('jre'));
    }

    public function update(Request $request, Jre $jre)
    {
        $request->validate([
            'notes' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        $jre->update($request->only(['notes', 'status']));

        return redirect()->route('jre.show', $jre)->with('success', 'JRE berhasil diupdate');
    }

    public function destroy(Jre $jre)
    {
        // Create destruction record
        ArchiveDestruction::create([
            'jre_id' => $jre->id,
            'arsip_id' => $jre->arsip_id,
            'destroyed_by' => Auth::id(),
            'destroyed_at' => Carbon::now(),
            'notes' => 'Arsip dimusnahkan dari JRE'
        ]);

        // Update JRE status to destroyed
        $jre->update([
            'status' => 'destroyed',
            'destroyed_at' => Carbon::now(),
            'destroyed_by' => Auth::id()
        ]);

        return redirect()->route('jre.index')->with('success', 'Arsip berhasil dimusnahkan');
    }

    public function recover(Request $request, Jre $jre)
    {
        $request->validate([
            'recovery_years' => 'required|integer|min:1|max:10'
        ]);

        // Use the recoverToArsip method from Jre model
        $recoveredArsip = $jre->recoverToArsip($request->recovery_years);

        if ($recoveredArsip) {
            return redirect()->route('arsip.index')->with('success', 'Arsip berhasil dipulihkan dari JRE dengan masa retensi ' . $request->recovery_years . ' tahun');
        } else {
            return back()->with('error', 'Gagal memulihkan arsip dari JRE');
        }
    }

    public function transfer(Request $request, $id)
    {
        $request->validate([
            'transfer_location' => 'required|string|max:255',
            'transfer_notes' => 'nullable|string'
        ]);

        $jre = Jre::findOrFail($id);
        $arsipId = $jre->arsip_id;

        // Update JRE status to transferred
        $jre->update([
            'status' => 'transferred',
            'transferred_at' => now(),
            'transferred_by' => Auth::id(),
            'transfer_notes' => $request->transfer_notes
        ]);

        // Create a record in pemindahans table to track the transfer
        \App\Models\Pemindahan::create([
            'arsip_id' => $arsipId,
            'user_id' => Auth::id(),
            'tingkat_perkembangan' => 'asli', // Default to 'asli'
            'jumlah_folder' => 1, // Default to 1
            'keterangan' => 'Lokasi: ' . $request->transfer_location . "\n" . ($request->transfer_notes ?: ''),
            'status' => 'completed',
            'completed_at' => now(),
            'completed_by' => Auth::id(),
            'catatan_admin' => 'Pemindahan langsung dari JRE'
        ]);

        // NOTE: Jangan ubah is_archived_to_jre karena arsip yang dipindahkan
        // tidak seharusnya kembali ke daftar arsip aktif
        // Scope active() sudah diupdate untuk mengecualikan arsip yang dipindahkan

        return redirect()->route('jre.index')->with('success', 'Arsip berhasil dipindahkan ke lokasi: ' . $request->transfer_location);
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'jre_ids' => 'required|array',
            'jre_ids.*' => 'exists:jres,id'
        ]);

        $destroyedCount = 0;

        foreach ($request->jre_ids as $jreId) {
            $jre = Jre::find($jreId);
            if ($jre && $jre->status !== 'destroyed') {
                // Create destruction record
                ArchiveDestruction::create([
                    'jre_id' => $jre->id,
                    'arsip_id' => $jre->arsip_id,
                    'destroyed_by' => Auth::id(),
                    'destroyed_at' => Carbon::now(),
                    'notes' => 'Arsip dimusnahkan dari JRE (bulk action)'
                ]);

                // Update JRE status
                $jre->update([
                    'status' => 'destroyed',
                    'destroyed_at' => Carbon::now(),
                    'destroyed_by' => Auth::id()
                ]);

                $destroyedCount++;
            }
        }

        return redirect()->route('jre.index')->with('success', "Berhasil memusnahkan {$destroyedCount} arsip");
    }

    public function getArchivedData(Request $request)
    {
        $arsipId = $request->get('arsip_id');

        if (!$arsipId) {
            return response()->json(['error' => 'Arsip ID is required'], 400);
        }

        $jre = Jre::where('arsip_id', $arsipId)->first();

        if (!$jre || !$jre->arsip_data) {
            return response()->json(['error' => 'No archived data found'], 404);
        }

        $arsipData = json_decode($jre->arsip_data, true);

        return response()->json([
            'success' => true,
            'data' => $arsipData
        ]);
    }

    public function destroyedArchives()
    {
        $destroyedJres = Jre::where('status', 'destroyed')
                           ->with('arsip')
                           ->latest('destroyed_at')
                           ->get();

        return view('jre.destroyed', compact('destroyedJres'));
    }

    public function transferredArchives()
    {
        $transferredJres = Jre::where('status', 'transferred')
                             ->with('arsip')
                             ->latest('transferred_at')
                             ->get();

        return view('jre.transferred', compact('transferredJres'));
    }

    public function destroyArchive(Request $request, $id)
    {
        $request->validate([
            'destruction_notes' => 'required|string|min:10'
        ]);

        $jre = Jre::findOrFail($id);

        // Check if already destroyed
        if ($jre->status === 'destroyed') {
            return redirect()->route('jre.edit', $jre->id)
                           ->with('error', 'Arsip ini sudah dimusnahkan sebelumnya.');
        }

        try {
            // Create destruction record
            $destruction = ArchiveDestruction::create([
                'arsip_id' => $jre->arsip_id,
                'jre_id' => $jre->id,
                'destruction_notes' => $request->destruction_notes,
                'user_id' => Auth::id(),
                'destroyed_at' => Carbon::now()
            ]);

            // Update JRE status
            $jre->update([
                'status' => 'destroyed',
                'notes' => $jre->notes . "\n\n[DIMUSNAHKAN] " . $request->destruction_notes
            ]);

            return redirect()->route('jre.index')
                           ->with('success', 'Arsip berhasil dimusnahkan dan dicatat dalam sistem.');

        } catch (\Exception $e) {
            return redirect()->route('jre.edit', $jre->id)
                           ->with('error', 'Terjadi kesalahan saat memusnahkan arsip: ' . $e->getMessage());
        }
    }

    public function recoverWithYears(Request $request, $id)
    {
        $request->validate([
            'recovery_years' => 'required|in:1,2,3,5,7,10,15,20,25,30,permanent'
        ]);

        try {
            $jre = Jre::findOrFail($id);
            $arsip = $jre->arsip;

            if (!$arsip) {
                return redirect()->route('jre.index')
                               ->with('error', 'Arsip tidak ditemukan.');
            }

            // Calculate new retention date
            $recoveryYears = $request->recovery_years;

            if ($recoveryYears === 'permanent') {
                $newRetentionDate = null;
                $retentionYearsValue = null;
                $recoveryText = 'permanen';
            } else {
                // Ensure we have an integer for addYears()
                $yearsInt = intval($recoveryYears);
                if ($yearsInt <= 0) {
                    throw new \Exception("Invalid years value: {$recoveryYears}");
                }
                $newRetentionDate = Carbon::now()->addYears($yearsInt);
                $retentionYearsValue = $yearsInt;
                $recoveryText = $recoveryYears . ' tahun';
            }

            // Update arsip status
            $arsip->update([
                'is_archived_to_jre' => false,
                'archived_to_jre_at' => null,
                'retention_date' => $newRetentionDate,
                'retention_years' => $retentionYearsValue,
                'has_retention_notification' => false
            ]);

            // Delete JRE record
            $jre->delete();

            return redirect()->route('jre.index')
                           ->with('success', "Arsip '{$arsip->nama_dokumen}' berhasil dipulihkan dengan masa retensi {$recoveryText}.");

        } catch (\Exception $e) {
            return redirect()->route('jre.edit', $id)
                           ->with('error', 'Terjadi kesalahan saat memulihkan arsip: ' . $e->getMessage());
        }
    }
}
