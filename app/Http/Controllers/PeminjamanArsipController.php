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

        $user = Auth::user();

        // Get borrowing records
        if ($user->role === 'peminjam') {
            $peminjamans = PeminjamanArsip::with('arsip')
                ->where('peminjam_user_id', $user->id)
                ->latest()
                ->get();
        } else {
            $peminjamans = PeminjamanArsip::with('arsip')->latest()->get();
        }

        // Get available archives for borrowing
        $availableArsipsQuery = Arsip::where('is_archived_to_jre', false)
            ->whereDoesntHave('peminjaman', function($query) {
                $query->whereIn('status', ['dipinjam', 'terlambat']);
            });

        // Exclude user's own archives for all users
        // Admin can view all archives directly without borrowing
        if ($user->role === 'admin') {
            // For admin, show all available archives (they can view without borrowing)
            $availableArsips = $availableArsipsQuery->with('creator')->latest()->get();
        } else {
            // For other roles, exclude their own archives
            $availableArsips = $availableArsipsQuery
                ->where('created_by', '!=', $user->id)
                ->with('creator')
                ->latest()
                ->get();
        }

        return view('peminjaman.index', compact('peminjamans', 'availableArsips'));
    }

    public function create()
    {
        $user = Auth::user();

        // Get arsips that are not in JRE and not currently borrowed
        $arsipsQuery = Arsip::where('is_archived_to_jre', false)
            ->whereDoesntHave('peminjaman', function($query) {
                $query->whereIn('status', ['dipinjam', 'terlambat']);
            });

        // Untuk peminjam, hanya tampilkan arsip dari seksi lain (bukan milik sendiri)
        if ($user->role === 'peminjam') {
            $arsipsQuery->where('created_by', '!=', $user->id);
        }

        $arsips = $arsipsQuery->with('creator')->get();

        // Jika ada parameter arsip_id, ambil data arsip tersebut
        $selectedArsip = null;
        if (request()->has('arsip_id')) {
            $selectedArsip = Arsip::find(request('arsip_id'));

            // Check if peminjam can borrow this arsip
            if ($user->role === 'peminjam' && $selectedArsip && $selectedArsip->created_by === $user->id) {
                return redirect()->route('peminjaman.create')->with('error', 'Anda tidak dapat meminjam arsip milik sendiri.');
            }
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
        $peminjamanData = [
            'arsip_id' => $request->arsip_id,
            'peminjam_user_id' => Auth::id(),
            'peminjam' => $request->peminjam,
            'jabatan' => $request->jabatan,
            'departemen' => Auth::user()->role === 'peminjam' ? Auth::user()->department : $request->departemen,
            'kontak' => $request->kontak,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'batas_waktu' => $request->batas_waktu,
            'tujuan_peminjaman' => $request->tujuan_peminjaman,
            'catatan' => $request->catatan,
            'petugas_peminjaman' => Auth::user()->role === 'peminjam' ? 'Self-Service' : Auth::user()->name,
        ];

        // Jika user adalah peminjam, status pending menunggu konfirmasi admin
        if (Auth::user()->role === 'peminjam') {
            $peminjamanData['status'] = 'pending';
            $peminjamanData['confirmation_status'] = 'pending';
        } else {
            // Jika admin/petugas langsung approve
            $peminjamanData['status'] = 'dipinjam';
            $peminjamanData['confirmation_status'] = 'approved';
            $peminjamanData['approved_by'] = Auth::id();
            $peminjamanData['approved_at'] = Carbon::now();
        }

        PeminjamanArsip::create($peminjamanData);

        $message = Auth::user()->role === 'peminjam'
            ? 'Permintaan peminjaman berhasil diajukan. Menunggu persetujuan admin.'
            : 'Peminjaman arsip berhasil dicatat';

        return redirect()->route('peminjaman.index')->with('success', $message);
    }

    public function show(PeminjamanArsip $peminjaman)
    {
        // Jika user adalah peminjam, cek apakah peminjaman ini miliknya
        if (Auth::user()->role === 'peminjam' && $peminjaman->peminjam_user_id != Auth::id()) {
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
            'status' => 'required|in:pending,dipinjam,dikembalikan,terlambat',
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
        // Hanya peminjam yang bisa mengembalikan arsip mereka sendiri
        if (Auth::user()->role !== 'peminjam') {
            return redirect()->route('peminjaman.index')->with('error', 'Hanya peminjam yang dapat mengembalikan arsip mereka sendiri.');
        }

        // Check if user has permission to return this loan
        if ($peminjaman->peminjam_user_id != Auth::id()) {
            return redirect()->route('peminjaman.index')->with('error', 'Anda tidak memiliki akses untuk mengembalikan peminjaman ini.');
        }

        // Check if the loan can be returned
        if ($peminjaman->status === 'dikembalikan') {
            return redirect()->route('peminjaman.show', $peminjaman->id)->with('error', 'Arsip ini sudah dikembalikan.');
        }

        if ($peminjaman->status === 'pending') {
            return redirect()->route('peminjaman.show', $peminjaman->id)->with('error', 'Peminjaman masih dalam status pending, tidak bisa dikembalikan.');
        }

        return view('peminjaman.return', compact('peminjaman'));
    }

    public function processReturn(Request $request, PeminjamanArsip $peminjaman)
    {
        // Hanya peminjam yang bisa mengembalikan arsip mereka sendiri
        if (Auth::user()->role !== 'peminjam') {
            return redirect()->route('peminjaman.index')->with('error', 'Hanya peminjam yang dapat mengembalikan arsip mereka sendiri.');
        }

        // Check if user has permission to return this loan
        if ($peminjaman->peminjam_user_id != Auth::id()) {
            return redirect()->route('peminjaman.index')->with('error', 'Anda tidak memiliki akses untuk mengembalikan peminjaman ini.');
        }

        // Check if the loan can be returned
        if ($peminjaman->status === 'dikembalikan') {
            return redirect()->route('peminjaman.show', $peminjaman->id)->with('error', 'Arsip ini sudah dikembalikan.');
        }

        if ($peminjaman->status === 'pending') {
            return redirect()->route('peminjaman.show', $peminjaman->id)->with('error', 'Peminjaman masih dalam status pending, tidak bisa dikembalikan.');
        }

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

    public function pending()
    {
        // Hanya admin yang bisa melihat pending confirmations
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('peminjaman.index')->with('error', 'Akses ditolak. Hanya admin yang dapat melihat permintaan pending.');
        }

        $peminjamans = PeminjamanArsip::with(['arsip', 'user'])
            ->where('confirmation_status', 'pending')
            ->latest()
            ->get();

        return view('peminjaman.pending', compact('peminjamans'));
    }

    public function approve(PeminjamanArsip $peminjaman)
    {
        // Hanya admin yang bisa approve
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('peminjaman.index')->with('error', 'Akses ditolak. Hanya admin yang dapat menyetujui peminjaman.');
        }

        // Cek apakah arsip masih tersedia
        if ($peminjaman->arsip->isCurrentlyBorrowed()) {
            return redirect()->back()->with('error', 'Arsip ini sudah dipinjam oleh orang lain.');
        }

        $peminjaman->approve(Auth::id());
        $peminjaman->update(['status' => 'dipinjam']);

        return redirect()->route('peminjaman.pending')->with('success', 'Permintaan peminjaman berhasil disetujui.');
    }

    public function reject(Request $request, PeminjamanArsip $peminjaman)
    {
        // Hanya admin yang bisa reject
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('peminjaman.index')->with('error', 'Akses ditolak. Hanya admin yang dapat menolak peminjaman.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $peminjaman->reject(Auth::id(), $request->rejection_reason);

        return redirect()->route('peminjaman.pending')->with('success', 'Permintaan peminjaman berhasil ditolak.');
    }
}
