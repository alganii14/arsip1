<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\ArsipController;
use App\Http\Controllers\JreController;
use App\Http\Controllers\PeminjamanArsipController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirect root to dashboard
Route::get('/', function () {
    return redirect('/dashboard');
})->middleware('auth');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/signup', function () {
        return view('account-pages.signup');
    })->name('signup');

    Route::get('/signin', function () {
        return view('account-pages.signin');
    })->name('signin');

    Route::get('/sign-up', [RegisterController::class, 'create'])->name('sign-up');
    Route::post('/sign-up', [RegisterController::class, 'store']);

    Route::get('/sign-in', [LoginController::class, 'create'])->name('sign-in');
    Route::post('/sign-in', [LoginController::class, 'store']);

    Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'store']);
});

// Routes for all authenticated users
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Logout
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // Profile
    Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile');
    Route::post('/profile/change-password', [ProfileController::class, 'updatePassword'])->name('profile.change-password');

    Route::get('/laravel-examples/user-profile', [ProfileController::class, 'index'])->name('users.profile');
    Route::put('/laravel-examples/user-profile/update', [ProfileController::class, 'update'])->name('users.update');

    // Routes accessible by all users (peminjam, petugas, admin)
    // Arsip - Read Only for peminjam
    Route::get('arsip', [ArsipController::class, 'index'])->name('arsip.index');
    Route::get('arsip/{arsip}/detail', [ArsipController::class, 'detail'])->name('arsip.detail');
    Route::get('arsip/{arsip}/download', [ArsipController::class, 'download'])->name('arsip.download');

    // Peminjaman - Limited access for peminjam
    Route::get('peminjaman', [PeminjamanArsipController::class, 'index'])->name('peminjaman.index');
    Route::get('peminjaman/create', [PeminjamanArsipController::class, 'create'])->name('peminjaman.create');
    Route::post('peminjaman', [PeminjamanArsipController::class, 'store'])->name('peminjaman.store');
    Route::get('peminjaman/{peminjaman}', [PeminjamanArsipController::class, 'show'])->name('peminjaman.show');

    // Routes accessible only by petugas and admin
    Route::middleware('role:petugas,admin')->group(function () {
        // Tables
        Route::get('/tables', function () {
            return view('tables');
        })->name('tables');

        Route::get('/wallet', function () {
            return view('wallet');
        })->name('wallet');

        Route::get('/RTL', function () {
            return view('RTL');
        })->name('RTL');

        // Arsip - Full CRUD
        Route::get('arsip/create', [ArsipController::class, 'create'])->name('arsip.create');
        Route::post('arsip', [ArsipController::class, 'store'])->name('arsip.store');
        Route::get('arsip/{arsip}/edit', [ArsipController::class, 'edit'])->name('arsip.edit');
        Route::put('arsip/{arsip}', [ArsipController::class, 'update'])->name('arsip.update');
        Route::delete('arsip/{arsip}', [ArsipController::class, 'destroy'])->name('arsip.destroy');
        Route::post('arsip/check-notifications', [ArsipController::class, 'checkNotifications'])->name('arsip.check-notifications');
        Route::post('arsip/extract-number', [ArsipController::class, 'extractDocumentNumber'])->name('arsip.extract-number');
        // Tambahkan route ini di bagian routes yang dapat diakses oleh semua user
        Route::get('arsip/{arsip}/detail', [ArsipController::class, 'detail'])->name('arsip.detail');

        // JRE - Full access
        Route::resource('jre', JreController::class);
        Route::post('jre/check-retention', [JreController::class, 'checkRetention'])->name('jre.check-retention');
        Route::post('jre/{jre}/recover', [JreController::class, 'recover'])->name('jre.recover');
        Route::post('jre/{jre}/destroy-archive', [JreController::class, 'destroyArchive'])->name('jre.destroy-archive');
        Route::post('jre/{jre}/transfer', [JreController::class, 'transfer'])->name('jre.transfer');

        // Peminjaman - Additional functionality
        Route::get('peminjaman/{peminjaman}/edit', [PeminjamanArsipController::class, 'edit'])->name('peminjaman.edit');
        Route::put('peminjaman/{peminjaman}', [PeminjamanArsipController::class, 'update'])->name('peminjaman.update');
        Route::delete('peminjaman/{peminjaman}', [PeminjamanArsipController::class, 'destroy'])->name('peminjaman.destroy');
        Route::get('peminjaman/{peminjaman}/return', [PeminjamanArsipController::class, 'returnForm'])->name('peminjaman.return-form');
        Route::post('peminjaman/{peminjaman}/process-return', [PeminjamanArsipController::class, 'processReturn'])->name('peminjaman.process-return');
        Route::post('peminjaman/check-overdue', [PeminjamanArsipController::class, 'checkOverdue'])->name('peminjaman.check-overdue');

        // Peminjaman - Admin Confirmation
        Route::get('peminjaman-pending', [PeminjamanArsipController::class, 'pending'])->name('peminjaman.pending');
        Route::post('peminjaman/{peminjaman}/approve', [PeminjamanArsipController::class, 'approve'])->name('peminjaman.approve');
        Route::post('peminjaman/{peminjaman}/reject', [PeminjamanArsipController::class, 'reject'])->name('peminjaman.reject');
    });

    // Routes accessible only by admin
    Route::middleware('role:admin')->group(function () {
        // User Management
        Route::get('/laravel-examples/users-management', [UserController::class, 'index'])->name('users-management');
        Route::resource('users', UserController::class);
    });
});
