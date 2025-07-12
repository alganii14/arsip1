<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Arsip;
use App\Models\User;
use App\Models\PeminjamanArsip;
use App\Models\Jre;
use App\Models\ArchiveDestruction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        try {
            if ($user->role === 'peminjam') {
                // Dashboard untuk Peminjam

                // Total Arsip - arsip yang dimasukkan oleh seksi tersebut (user peminjam)
                $totalArsip = Arsip::where('created_by', $user->id)->count();

                // Total Pinjaman Seksi - peminjaman yang dilakukan oleh user ini
                $totalPeminjam = PeminjamanArsip::where('peminjam_user_id', $user->id)->count();

                // Untuk peminjam, tidak ada data JRE dan arsip dimusnahkan
                $totalPengguna = 0;
                $totalArsipJre = 0;
                $totalArsipMusnahkan = 0;

            } else {
                // Dashboard untuk Admin/Petugas (dashboard asli)

                // Total Arsip
                $totalArsip = Arsip::count();

                // Total Pengguna (semua user)
                $totalPengguna = User::count();

                // Total Peminjam (user yang pernah meminjam)
                $totalPeminjam = PeminjamanArsip::distinct('peminjam_user_id')->count('peminjam_user_id');

                // Total Arsip di JRE (tidak termasuk yang sudah dimusnahkan)
                $totalArsipJre = Jre::active()->count();

                // Total Arsip yang Dimusnahkan
                $totalArsipMusnahkan = ArchiveDestruction::count();
            }

        } catch (\Exception $e) {
            // Fallback values in case of error
            $totalArsip = 0;
            $totalPengguna = 0;
            $totalPeminjam = 0;
            $totalArsipJre = 0;
            $totalArsipMusnahkan = 0;
        }

        return view('dashboard', compact(
            'totalArsip',
            'totalPengguna',
            'totalPeminjam',
            'totalArsipJre',
            'totalArsipMusnahkan'
        ));
    }
}
