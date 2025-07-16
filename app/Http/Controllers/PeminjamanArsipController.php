<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanArsip;
use App\Models\Arsip;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PeminjamanArsipExport;
use Barryvdh\DomPDF\Facade\Pdf;

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
        if ($user->role === 'unit_pengelola') {
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
                $query->whereIn('status', ['dipinjam', 'terlambat'])
                      ->where('confirmation_status', 'approved');
            })
            ->whereDoesntHave('pemindahan'); // Exclude archives that have been transferred

        // Exclude user's own archives for all users
        // Unit kerja can view all archives directly without borrowing
        if ($user->role === 'unit_kerja') {
            // For unit kerja, show all available archives (they can view without borrowing)
            $availableArsips = $availableArsipsQuery->with('creator')->latest()->get();
        } else {
            // For other roles, exclude their own archives and archives they already borrowed
            $availableArsips = $availableArsipsQuery
                ->where('created_by', '!=', $user->id)
                ->whereDoesntHave('peminjaman', function($query) use ($user) {
                    $query->where('peminjam_user_id', $user->id)
                          ->whereIn('status', ['pending', 'dipinjam', 'terlambat'])
                          ->where('confirmation_status', '!=', 'rejected');
                })
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
                $query->whereIn('status', ['dipinjam', 'terlambat'])
                      ->where('confirmation_status', 'approved');
            })
            ->whereDoesntHave('pemindahan'); // Exclude archives that have been transferred

        // Untuk unit pengelola, hanya tampilkan arsip dari seksi lain (bukan milik sendiri)
        if ($user->role === 'unit_pengelola') {
            $arsipsQuery->where('created_by', '!=', $user->id);
        }

        $arsips = $arsipsQuery->with('creator')->get();

        // Jika ada parameter arsip_id, ambil data arsip tersebut
        $selectedArsip = null;
        if (request()->has('arsip_id')) {
            $selectedArsip = Arsip::find(request('arsip_id'));

            // Check if unit pengelola can borrow this arsip
            if ($user->role === 'unit_pengelola' && $selectedArsip && $selectedArsip->created_by === $user->id) {
                return redirect()->route('peminjaman.create')->with('error', 'Anda tidak dapat meminjam arsip milik sendiri.');
            }
        }

        return view('peminjaman.create', compact('arsips', 'selectedArsip'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'arsip_id' => 'required|exists:arsips,id',
            'jenis_peminjaman' => 'nullable|in:fisik,digital,umum',
            'peminjam' => 'required|string|max:255',
            'departemen' => 'nullable|string|max:255',
            'kontak' => 'required|string|max:255',
            'tanggal_pinjam' => 'required|date',
            'batas_waktu' => 'required|date|after_or_equal:tanggal_pinjam',
            'tujuan_peminjaman' => 'nullable|string',
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
            'departemen' => Auth::user()->role === 'peminjam' ? Auth::user()->department : $request->departemen,
            'kontak' => $request->kontak,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'batas_waktu' => $request->batas_waktu,
            'tujuan_peminjaman' => $request->tujuan_peminjaman,
            'petugas_peminjaman' => Auth::user()->role === 'peminjam' ? 'Self-Service' : Auth::user()->name,
            'jenis_peminjaman' => $request->jenis_peminjaman ?? 'fisik',
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

    public function storeDigital(Request $request)
    {
        $request->validate([
            'arsip_id' => 'required|exists:arsips,id',
        ]);

        $user = Auth::user();
        $arsip = Arsip::findOrFail($request->arsip_id);

        // Check if user can borrow this arsip
        if ($user->role === 'peminjam' && $arsip->created_by === $user->id) {
            return redirect()->route('peminjaman.index')->with('error', 'Anda tidak dapat meminjam arsip milik sendiri.');
        }

        // Check if arsip is already borrowed by anyone (including pending approved)
        if ($arsip->isCurrentlyBorrowed()) {
            return redirect()->back()->with('error', 'Arsip ini sedang dipinjam oleh orang lain.');
        }

        // Check if user already has an active borrowing for this arsip (more comprehensive check)
        $existingBorrowing = PeminjamanArsip::where('arsip_id', $request->arsip_id)
            ->where('peminjam_user_id', $user->id)
            ->where(function($query) {
                $query->whereIn('status', ['pending', 'dipinjam', 'terlambat'])
                      ->orWhere(function($subQuery) {
                          $subQuery->where('confirmation_status', 'approved')
                                   ->where('status', '!=', 'dikembalikan');
                      });
            })
            ->where('confirmation_status', '!=', 'rejected')
            ->first();

        if ($existingBorrowing) {
            return redirect()->route('peminjaman.show', $existingBorrowing->id)
                ->with('info', 'Anda sudah meminjam arsip ini. Berikut adalah detail peminjaman yang aktif.');
        }

        // Double check: ensure no duplicate digital borrowing exists
        $duplicateDigitalBorrowing = PeminjamanArsip::where('arsip_id', $request->arsip_id)
            ->where('peminjam_user_id', $user->id)
            ->where('jenis_peminjaman', 'digital')
            ->whereIn('status', ['pending', 'dipinjam', 'terlambat'])
            ->where('confirmation_status', 'approved')
            ->first();

        if ($duplicateDigitalBorrowing) {
            return redirect()->route('peminjaman.show', $duplicateDigitalBorrowing->id)
                ->with('error', 'Anda sudah meminjam arsip ini secara digital. Tidak bisa meminjam ulang.');
        }

        // Create digital borrowing with minimal data - always approved
        $peminjamanData = [
            'arsip_id' => $request->arsip_id,
            'peminjam_user_id' => $user->id,
            'peminjam' => $user->name,
            'jabatan' => $user->jabatan ?? '',
            'departemen' => $user->department ?? '',
            'kontak' => $user->email ?? $user->phone ?? '',
            'tanggal_pinjam' => Carbon::now(),
            'batas_waktu' => Carbon::now()->addDays(7), // Default 7 days for digital
            'tujuan_peminjaman' => 'Peminjaman Digital',
            'catatan' => 'Peminjaman digital - disetujui otomatis',
            'petugas_peminjaman' => 'Sistem Digital',
            'jenis_peminjaman' => 'digital',
            'status' => 'dipinjam', // Langsung dipinjam tanpa pending
            'confirmation_status' => 'approved', // Langsung approved
            'approved_by' => $user->id,
            'approved_at' => Carbon::now(),
        ];

        $peminjaman = PeminjamanArsip::create($peminjamanData);

        return redirect()->route('peminjaman.show', $peminjaman->id)
            ->with('success', 'Arsip berhasil dipinjam secara digital! Anda dapat langsung melihat dan mengunduh arsip.');
    }

    public function show(PeminjamanArsip $peminjaman)
    {
        // Jika user adalah peminjam, cek apakah peminjaman ini miliknya
        if (Auth::user()->role === 'peminjam' && $peminjaman->peminjam_user_id != Auth::id()) {
            return redirect()->route('peminjaman.index')->with('error', 'Anda tidak memiliki akses ke data peminjaman ini.');
        }

        // Cek jenis peminjaman - jika fisik, tidak bisa lihat arsip digital
        if ($peminjaman->jenis_peminjaman === 'fisik') {
            // Untuk peminjaman fisik, hanya tampilkan detail peminjaman tanpa akses ke file arsip
            $peminjaman->load('arsip');
            return view('peminjaman.show-fisik', compact('peminjaman'));
        }

        // Untuk peminjaman digital, bisa akses file arsip
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
            'departemen' => 'nullable|string|max:255',
            'kontak' => 'required|string|max:255',
            'tanggal_pinjam' => 'required|date',
            'batas_waktu' => 'required|date|after_or_equal:tanggal_pinjam',
            'status' => 'required|in:pending,dipinjam,dikembalikan,terlambat',
            'tujuan_peminjaman' => 'nullable|string',
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
        // Untuk peminjaman fisik, hanya admin yang bisa melakukan pengembalian
        if ($peminjaman->jenis_peminjaman === 'fisik' && Auth::user()->role !== 'admin') {
            return redirect()->route('peminjaman.index')->with('error', 'Pengembalian arsip fisik hanya dapat dilakukan oleh admin.');
        }

        // Untuk peminjaman digital, peminjam bisa mengembalikan sendiri
        if ($peminjaman->jenis_peminjaman === 'digital' && $peminjaman->peminjam_user_id != Auth::id()) {
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
        // Untuk peminjaman fisik, hanya admin yang bisa melakukan pengembalian
        if ($peminjaman->jenis_peminjaman === 'fisik' && Auth::user()->role !== 'admin') {
            return redirect()->route('peminjaman.index')->with('error', 'Pengembalian arsip fisik hanya dapat dilakukan oleh admin.');
        }

        // Untuk peminjaman digital, peminjam bisa mengembalikan sendiri
        if ($peminjaman->jenis_peminjaman === 'digital' && $peminjaman->peminjam_user_id != Auth::id()) {
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
        $user = Auth::user();
        
        // Hanya unit kerja yang bisa reject
        if ($user->role !== 'unit_kerja') {
            return redirect()->route('peminjaman.index')->with('error', 'Akses ditolak. Hanya unit kerja yang dapat menolak peminjaman.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $peminjaman->reject(Auth::id(), $request->rejection_reason);

        return redirect()->route('peminjaman.pending')->with('success', 'Permintaan peminjaman berhasil ditolak.');
    }

    public function exportExcel(Request $request)
    {
        $user = Auth::user();

        // Get borrowing records based on user role
        if ($user->role === 'unit_pengelola') {
            $peminjamans = PeminjamanArsip::with(['arsip', 'user'])
                ->where('peminjam_user_id', $user->id)
                ->latest()
                ->get();
        } else {
            $peminjamans = PeminjamanArsip::with(['arsip', 'user'])->latest()->get();
        }

        return Excel::download(
            new PeminjamanArsipExport($peminjamans),
            'laporan-peminjaman-arsip-' . date('Y-m-d') . '.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $user = Auth::user();

        // Get borrowing records based on user role
        if ($user->role === 'peminjam') {
            $peminjamans = PeminjamanArsip::with(['arsip', 'user'])
                ->where('peminjam_user_id', $user->id)
                ->latest()
                ->get();
        } else {
            $peminjamans = PeminjamanArsip::with(['arsip', 'user'])->latest()->get();
        }

        $data = [
            'peminjamans' => $peminjamans,
            'title' => 'Laporan Riwayat Peminjaman Arsip',
            'date' => date('d/m/Y'),
            'user' => $user
        ];

        $pdf = Pdf::loadView('reports.peminjaman-arsip', $data);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('laporan-peminjaman-arsip-' . date('Y-m-d') . '.pdf');
    }
}
