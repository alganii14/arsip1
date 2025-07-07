<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Arsip;
use App\Models\User;
use App\Models\PeminjamanArsip;
use App\Models\Jre;
use App\Models\ArchiveDestruction;

class DashboardController extends Controller
{
    public function index()
    {
        try {
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
