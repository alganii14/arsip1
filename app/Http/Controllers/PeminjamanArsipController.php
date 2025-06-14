<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanArsip;
use App\Models\Arsip;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PeminjamanArsipController extends Controller
{
    public function index()
    {
        // Update status for all active borrowings
        $activeBorrowings = PeminjamanArsip::where('status', 'dipinjam')->get();
        foreach ($activeBorrowings as $peminjaman) {
            $peminjaman->updateStatus();
        }
        
        // Jika user adalah peminjam, hanya tampilkan peminjaman miliknya
        if (Auth::user()->isPeminjam()) {
            $peminjamans = PeminjamanArsip::with('arsip')
                ->where('peminjam_user_id', Auth::id())
                ->latest()
                ->get();
        } else {
            // Jika admin atau petugas, tampilkan semua
            $peminjamans = PeminjamanArsip::with('arsip')->latest()->get();
        }
        
        return view('peminjaman.index', compact('peminjamans'));
    }

    public function create()
    {
        // Get arsips that are not in JRE and not currently borrowed
        $arsips = Arsip::where('is_archived_to_jre', false)
            ->whereDoesntHave('peminjaman', function($query) {
                $query->whereIn('status', ['dipinjam', 'terlambat']);
            })
            ->get();
        
        // Jika ada parameter arsip_id, ambil data arsip tersebut
        $selectedArsip = null;
        if (request()->has('arsip_id')) {
            $selectedArsip = Arsip::find(request('arsip_id'));
        }
            
        return view('peminjaman.create', compact('arsips', 'selectedArsip'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'arsip_id' => 'required|exists:arsips,id',
            'peminjam' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'departemen' => 'nullable|string|max:255',
            'kontak' => 'required|string|max:255',
            'tanggal_pinjam' => 'required|date',
            'batas_waktu' => 'required|date|after_or_equal:tanggal_pinjam',
            'tujuan_peminjaman' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        // Check if arsip is already borrowed
        $arsip = Arsip::findOrFail($request->arsip_id);
        if ($arsip->isCurrentlyBorrowed()) {
            return redirect()->back()->with('error', 'Arsip ini sedang dipinjam oleh orang lain.');
        }

        // Create new peminjaman
        PeminjamanArsip::create([
            'arsip_id' => $request->arsip_id,
            'peminjam_user_id' => Auth::id(), // Tambahkan user ID yang sedang login
            'peminjam' => $request->peminjam,
            'jabatan' => $request->jabatan,
            'departemen' => Auth::user()->isPeminjam() ? Auth::user()->department : $request->departemen, // Gunakan departemen user jika peminjam
            'kontak' => $request->kontak,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'batas_waktu' => $request->batas_waktu,
            'tujuan_peminjaman' => $request->tujuan_peminjaman,
            'catatan' => $request->catatan,
            'status' => 'dipinjam',
            'petugas_peminjaman' => Auth::user()->isPeminjam() ? 'Self-Service' : Auth::user()->name,
        ]);

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman arsip berhasil dicatat');
    }

    public function show(PeminjamanArsip $peminjaman)
    {
        // Jika user adalah peminjam, cek apakah peminjaman ini miliknya
        if (Auth::user()->isPeminjam() && $peminjaman->peminjam_user_id != Auth::id()) {
            return redirect()->route('peminjaman.index')->with('error', 'Anda tidak memiliki akses ke data peminjaman ini.');
        }
        
        $peminjaman->load('arsip');
        return view('peminjaman.show', compact('peminjaman'));
    }

    public function edit(PeminjamanArsip $peminjaman)
    {
        $arsips = Arsip::all();
        return view('peminjaman.edit', compact('peminjaman', 'arsips'));
    }

    public function update(Request $request, PeminjamanArsip $peminjaman)
    {
        $request->validate([
            'arsip_id' => 'required|exists:arsips,id',
            'peminjam' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'departemen' => 'nullable|string|max:255',
            'kontak' => 'required|string|max:255',
            'tanggal_pinjam' => 'required|date',
            'batas_waktu' => 'required|date|after_or_equal:tanggal_pinjam',
            'status' => 'required|in:dipinjam,dikembalikan,terlambat',
            'tujuan_peminjaman' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        // If changing arsip_id, check if new arsip is available
        if ($request->arsip_id != $peminjaman->arsip_id) {
            $arsip = Arsip::findOrFail($request->arsip_id);
            if ($arsip->isCurrentlyBorrowed()) {
                return redirect()->back()->with('error', 'Arsip ini sedang dipinjam oleh orang lain.');
            }
        }

        $peminjaman->update($request->all());

        return redirect()->route('peminjaman.index')->with('success', 'Data peminjaman berhasil diperbarui');
    }

    public function destroy(PeminjamanArsip $peminjaman)
    {
        $peminjaman->delete();
        return redirect()->route('peminjaman.index')->with('success', 'Data peminjaman berhasil dihapus');
    }

    public function returnForm(PeminjamanArsip $peminjaman)
    {
        return view('peminjaman.return', compact('peminjaman'));
    }

    public function processReturn(Request $request, PeminjamanArsip $peminjaman)
    {
        $request->validate([
            'tanggal_kembali' => 'required|date',
            'catatan' => 'nullable|string',
        ]);

        $peminjaman->tanggal_kembali = $request->tanggal_kembali;
        $peminjaman->status = 'dikembalikan';
        $peminjaman->catatan = $peminjaman->catatan . "\n\nCatatan Pengembalian: " . $request->catatan;
        $peminjaman->petugas_pengembalian = Auth::user()->name;
        $peminjaman->save();

        return redirect()->route('peminjaman.index')->with('success', 'Arsip berhasil dikembalikan');
    }

    public function checkOverdue()
    {
        $count = 0;
        $peminjamans = PeminjamanArsip::where('status', 'dipinjam')->get();
        
        foreach ($peminjamans as $peminjaman) {
            if ($peminjaman->isOverdue()) {
                $peminjaman->status = 'terlambat';
                $peminjaman->save();
                $count++;
            }
        }
        
        return redirect()->back()->with('success', "$count peminjaman telah diperbarui statusnya menjadi terlambat");
    }
}