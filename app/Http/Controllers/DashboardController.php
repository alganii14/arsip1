<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Arsip;
use App\Models\User;
use App\Models\PeminjamanArsip;
use App\Models\Jre;
use App\Models\ArchiveDestruction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        try {
            if ($user->role === 'unit_pengelola') {
                // Dashboard untuk Unit Pengelola (Peminjam)

                // Total Arsip Seksi - arsip aktif yang dimasukkan oleh seksi tersebut
                $totalArsip = Arsip::active()
                    ->where('created_by', $user->id)
                    ->count();

                // Total Pinjaman Seksi - peminjaman yang dilakukan oleh user ini
                $totalPeminjam = PeminjamanArsip::where('peminjam_user_id', $user->id)->count();

                // Total Peminjaman Menunggu Persetujuan - peminjaman yang dibuat user tapi belum disetujui
                $totalPending = PeminjamanArsip::where('peminjam_user_id', $user->id)
                    ->where('confirmation_status', 'pending')
                    ->count();

                // Total Arsip Tersedia untuk Dipinjam dari seksi lain (hanya arsip aktif dan tidak sedang dipinjam)
                $totalArsipTersedia = Arsip::active()
                    ->where('created_by', '!=', $user->id)
                    ->whereDoesntHave('peminjaman', function($query) {
                        $query->whereIn('status', ['dipinjam', 'terlambat'])
                              ->where('confirmation_status', 'approved');
                    })
                    ->count();

                // Untuk unit pengelola, tidak ada data untuk pengguna total, JRE dan arsip dimusnahkan
                $totalPengguna = 0;
                $totalArsipJre = 0;
                $totalArsipMusnahkan = 0;

                // Data untuk grafik unit pengelola (arsip sendiri dan peminjaman)
                $statsDaily = $this->getDailyStatsPeminjam($user->id);
                $statsMonthly = $this->getMonthlyStatsPeminjam($user->id);
                $statsYearly = $this->getYearlyStatsPeminjam($user->id);

            } else {
                // Dashboard untuk Unit Kerja (Admin/Petugas)

                // Total Arsip Aktif - menggunakan scope active yang sama dengan daftar arsip
                $totalArsip = Arsip::active()->count();

                // Total Pengguna (semua user)
                $totalPengguna = User::count();

                // Total Peminjam (user yang pernah meminjam)
                $totalPeminjam = PeminjamanArsip::distinct('peminjam_user_id')->count('peminjam_user_id');

                // Total Arsip di JRE (arsip yang sudah masuk masa retensi, belum dimusnahkan)
                $totalArsipJre = Jre::active()->count();

                // Total Arsip yang Dimusnahkan
                $totalArsipMusnahkan = ArchiveDestruction::count();

                // Untuk unit kerja, set default values untuk variabel unit pengelola
                $totalPending = 0;
                $totalArsipTersedia = 0;

                // Data untuk grafik admin/petugas
                $statsDaily = $this->getDailyStats();
                $statsMonthly = $this->getMonthlyStats();
                $statsYearly = $this->getYearlyStats();
            }

        } catch (\Exception $e) {
            // Fallback values in case of error
            $totalArsip = 0;
            $totalPengguna = 0;
            $totalPeminjam = 0;
            $totalArsipJre = 0;
            $totalArsipMusnahkan = 0;
            $totalPending = 0;
            $totalArsipTersedia = 0;
            $statsDaily = [
                'labels' => [], 
                'arsip' => [], 
                'users' => [], 
                'peminjaman' => [], 
                'jre' => [], 
                'destruction' => []
            ];
            $statsMonthly = $statsDaily;
            $statsYearly = $statsDaily;
        }

        return view('dashboard', compact(
            'totalArsip',
            'totalPengguna',
            'totalPeminjam',
            'totalArsipJre',
            'totalArsipMusnahkan',
            'totalPending',
            'totalArsipTersedia',
            'statsDaily',
            'statsMonthly',
            'statsYearly'
        ));
    }

    // Untuk Admin/Petugas: Statistik harian
    private function getDailyStats()
    {
        // Ambil data untuk 14 hari terakhir
        $startDate = Carbon::now()->subDays(13)->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        
        // Data arsip aktif per hari (menggunakan scope active)
        $arsipStats = DB::table('arsips')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->where('is_archived_to_jre', false)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('pemindahans')
                      ->whereRaw('pemindahans.arsip_id = arsips.id')
                      ->whereIn('status', ['approved', 'completed']);
            })
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('archive_destructions')
                      ->whereRaw('archive_destructions.arsip_id = arsips.id');
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();
        
        // Data user baru per hari
        $userStats = User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();
        
        // Data peminjaman per hari
        $peminjamanStats = PeminjamanArsip::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();
        
        // Data arsip masuk JRE per hari
        $jreStats = Arsip::select(DB::raw('DATE(archived_to_jre_at) as date'), DB::raw('count(*) as total'))
            ->where('is_archived_to_jre', true)
            ->whereNotNull('archived_to_jre_at')
            ->whereBetween('archived_to_jre_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(archived_to_jre_at)'))
            ->orderBy('date')
            ->get();
        
        // Data arsip dimusnahkan per hari
        $destructionStats = ArchiveDestruction::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();
        
        // Persiapkan data untuk grafik
        $period = collect(Carbon::parse($startDate)->daysUntil($endDate)->toArray());
        
        $labels = $period->map(function ($date) {
            return $date->format('d/m');
        })->toArray();
        
        $arsipData = $this->prepareChartData($period, $arsipStats);
        $userData = $this->prepareChartData($period, $userStats);
        $peminjamanData = $this->prepareChartData($period, $peminjamanStats);
        $jreData = $this->prepareChartData($period, $jreStats);
        $destructionData = $this->prepareChartData($period, $destructionStats);
        
        return [
            'labels' => $labels,
            'arsip' => $arsipData,
            'users' => $userData,
            'peminjaman' => $peminjamanData,
            'jre' => $jreData,
            'destruction' => $destructionData
        ];
    }
    
    // Untuk Admin/Petugas: Statistik bulanan
    private function getMonthlyStats()
    {
        // Ambil data untuk 12 bulan terakhir
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        
        // Data arsip aktif per bulan (menggunakan scope active)
        $arsipStats = DB::table('arsips')
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as total')
            )
            ->where('is_archived_to_jre', false)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('pemindahans')
                      ->whereRaw('pemindahans.arsip_id = arsips.id')
                      ->whereIn('status', ['approved', 'completed']);
            })
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('archive_destructions')
                      ->whereRaw('archive_destructions.arsip_id = arsips.id');
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        // Data user baru per bulan
        $userStats = User::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as total')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        // Data peminjaman per bulan
        $peminjamanStats = PeminjamanArsip::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as total')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        // Data arsip masuk JRE per bulan
        $jreStats = Arsip::select(
                DB::raw('YEAR(archived_to_jre_at) as year'),
                DB::raw('MONTH(archived_to_jre_at) as month'),
                DB::raw('count(*) as total')
            )
            ->where('is_archived_to_jre', true)
            ->whereNotNull('archived_to_jre_at')
            ->whereBetween('archived_to_jre_at', [$startDate, $endDate])
            ->groupBy(DB::raw('YEAR(archived_to_jre_at)'), DB::raw('MONTH(archived_to_jre_at)'))
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        // Data arsip dimusnahkan per bulan
        $destructionStats = ArchiveDestruction::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as total')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        // Persiapkan data untuk grafik
        $period = collect(Carbon::parse($startDate)->monthsUntil($endDate)->toArray());
        
        $labels = $period->map(function ($date) {
            return $date->format('M Y');
        })->toArray();
        
        $arsipData = $this->prepareMonthlyChartData($period, $arsipStats);
        $userData = $this->prepareMonthlyChartData($period, $userStats);
        $peminjamanData = $this->prepareMonthlyChartData($period, $peminjamanStats);
        $jreData = $this->prepareMonthlyChartData($period, $jreStats);
        $destructionData = $this->prepareMonthlyChartData($period, $destructionStats);
        
        return [
            'labels' => $labels,
            'arsip' => $arsipData,
            'users' => $userData,
            'peminjaman' => $peminjamanData,
            'jre' => $jreData,
            'destruction' => $destructionData
        ];
    }
    
    // Untuk Admin/Petugas: Statistik tahunan
    private function getYearlyStats()
    {
        // Ambil data untuk 5 tahun terakhir
        $startYear = Carbon::now()->subYears(4)->year;
        $endYear = Carbon::now()->year;
        
        // Data arsip aktif per tahun (menggunakan scope active)
        $arsipStats = DB::table('arsips')
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('count(*) as total')
            )
            ->where('is_archived_to_jre', false)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('pemindahans')
                      ->whereRaw('pemindahans.arsip_id = arsips.id')
                      ->whereIn('status', ['approved', 'completed']);
            })
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('archive_destructions')
                      ->whereRaw('archive_destructions.arsip_id = arsips.id');
            })
            ->whereYear('created_at', '>=', $startYear)
            ->whereYear('created_at', '<=', $endYear)
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy('year')
            ->get();
        
        // Data user baru per tahun
        $userStats = User::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('count(*) as total')
            )
            ->whereYear('created_at', '>=', $startYear)
            ->whereYear('created_at', '<=', $endYear)
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy('year')
            ->get();
        
        // Data peminjaman per tahun
        $peminjamanStats = PeminjamanArsip::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('count(*) as total')
            )
            ->whereYear('created_at', '>=', $startYear)
            ->whereYear('created_at', '<=', $endYear)
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy('year')
            ->get();
        
        // Data arsip masuk JRE per tahun
        $jreStats = Arsip::select(
                DB::raw('YEAR(archived_to_jre_at) as year'),
                DB::raw('count(*) as total')
            )
            ->where('is_archived_to_jre', true)
            ->whereNotNull('archived_to_jre_at')
            ->whereYear('archived_to_jre_at', '>=', $startYear)
            ->whereYear('archived_to_jre_at', '<=', $endYear)
            ->groupBy(DB::raw('YEAR(archived_to_jre_at)'))
            ->orderBy('year')
            ->get();
        
        // Data arsip dimusnahkan per tahun
        $destructionStats = ArchiveDestruction::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('count(*) as total')
            )
            ->whereYear('created_at', '>=', $startYear)
            ->whereYear('created_at', '<=', $endYear)
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy('year')
            ->get();
        
        // Persiapkan data untuk grafik
        $years = range($startYear, $endYear);
        $labels = collect($years)->map(function ($year) {
            return (string) $year;
        })->toArray();
        
        $arsipData = $this->prepareYearlyChartData($years, $arsipStats);
        $userData = $this->prepareYearlyChartData($years, $userStats);
        $peminjamanData = $this->prepareYearlyChartData($years, $peminjamanStats);
        $jreData = $this->prepareYearlyChartData($years, $jreStats);
        $destructionData = $this->prepareYearlyChartData($years, $destructionStats);
        
        return [
            'labels' => $labels,
            'arsip' => $arsipData,
            'users' => $userData,
            'peminjaman' => $peminjamanData,
            'jre' => $jreData,
            'destruction' => $destructionData
        ];
    }
    
    // Untuk Peminjam: Statistik harian
    private function getDailyStatsPeminjam($userId)
    {
        // Ambil data untuk 14 hari terakhir
        $startDate = Carbon::now()->subDays(13)->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        
        // Data arsip aktif per hari (menggunakan scope active untuk user ini)
        $arsipStats = DB::table('arsips')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->where('created_by', $userId)
            ->where('is_archived_to_jre', false)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('pemindahans')
                      ->whereRaw('pemindahans.arsip_id = arsips.id')
                      ->whereIn('status', ['approved', 'completed']);
            })
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('archive_destructions')
                      ->whereRaw('archive_destructions.arsip_id = arsips.id');
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();
        
        // Data peminjaman per hari
        $peminjamanStats = PeminjamanArsip::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->where('peminjam_user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();
        
        // Persiapkan data untuk grafik
        $period = collect(Carbon::parse($startDate)->daysUntil($endDate)->toArray());
        
        $labels = $period->map(function ($date) {
            return $date->format('d/m');
        })->toArray();
        
        $arsipData = $this->prepareChartData($period, $arsipStats);
        $peminjamanData = $this->prepareChartData($period, $peminjamanStats);
        
        return [
            'labels' => $labels,
            'arsip' => $arsipData,
            'peminjaman' => $peminjamanData
        ];
    }
    
    // Untuk Peminjam: Statistik bulanan
    private function getMonthlyStatsPeminjam($userId)
    {
        // Ambil data untuk 12 bulan terakhir
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        
        // Data arsip aktif per bulan (menggunakan scope active untuk user ini)
        $arsipStats = DB::table('arsips')
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as total')
            )
            ->where('created_by', $userId)
            ->where('is_archived_to_jre', false)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('pemindahans')
                      ->whereRaw('pemindahans.arsip_id = arsips.id')
                      ->whereIn('status', ['approved', 'completed']);
            })
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('archive_destructions')
                      ->whereRaw('archive_destructions.arsip_id = arsips.id');
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        // Data peminjaman per bulan
        $peminjamanStats = PeminjamanArsip::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as total')
            )
            ->where('peminjam_user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        // Persiapkan data untuk grafik
        $period = collect(Carbon::parse($startDate)->monthsUntil($endDate)->toArray());
        
        $labels = $period->map(function ($date) {
            return $date->format('M Y');
        })->toArray();
        
        $arsipData = $this->prepareMonthlyChartData($period, $arsipStats);
        $peminjamanData = $this->prepareMonthlyChartData($period, $peminjamanStats);
        
        return [
            'labels' => $labels,
            'arsip' => $arsipData,
            'peminjaman' => $peminjamanData
        ];
    }
    
    // Untuk Peminjam: Statistik tahunan
    private function getYearlyStatsPeminjam($userId)
    {
        // Ambil data untuk 5 tahun terakhir
        $startYear = Carbon::now()->subYears(4)->year;
        $endYear = Carbon::now()->year;
        
        // Data arsip aktif per tahun (menggunakan scope active untuk user ini)
        $arsipStats = DB::table('arsips')
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('count(*) as total')
            )
            ->where('created_by', $userId)
            ->where('is_archived_to_jre', false)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('pemindahans')
                      ->whereRaw('pemindahans.arsip_id = arsips.id')
                      ->whereIn('status', ['approved', 'completed']);
            })
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('archive_destructions')
                      ->whereRaw('archive_destructions.arsip_id = arsips.id');
            })
            ->whereYear('created_at', '>=', $startYear)
            ->whereYear('created_at', '<=', $endYear)
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy('year')
            ->get();
        
        // Data peminjaman per tahun
        $peminjamanStats = PeminjamanArsip::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('count(*) as total')
            )
            ->where('peminjam_user_id', $userId)
            ->whereYear('created_at', '>=', $startYear)
            ->whereYear('created_at', '<=', $endYear)
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy('year')
            ->get();
        
        // Persiapkan data untuk grafik
        $years = range($startYear, $endYear);
        $labels = collect($years)->map(function ($year) {
            return (string) $year;
        })->toArray();
        
        $arsipData = $this->prepareYearlyChartData($years, $arsipStats);
        $peminjamanData = $this->prepareYearlyChartData($years, $peminjamanStats);
        
        return [
            'labels' => $labels,
            'arsip' => $arsipData,
            'peminjaman' => $peminjamanData
        ];
    }

    // Helper untuk menyiapkan data grafik harian
    private function prepareChartData($period, $stats)
    {
        return $period->map(function ($date) use ($stats) {
            $formattedDate = $date->format('Y-m-d');
            $stat = $stats->firstWhere('date', $formattedDate);
            return $stat ? $stat->total : 0;
        })->toArray();
    }
    
    // Helper untuk menyiapkan data grafik bulanan
    private function prepareMonthlyChartData($period, $stats)
    {
        return $period->map(function ($date) use ($stats) {
            $year = $date->year;
            $month = $date->month;
            $stat = $stats->first(function ($item) use ($year, $month) {
                return $item->year == $year && $item->month == $month;
            });
            return $stat ? $stat->total : 0;
        })->toArray();
    }
    
    // Helper untuk menyiapkan data grafik tahunan
    private function prepareYearlyChartData($years, $stats)
    {
        return collect($years)->map(function ($year) use ($stats) {
            $stat = $stats->firstWhere('year', $year);
            return $stat ? $stat->total : 0;
        })->toArray();
    }
}
