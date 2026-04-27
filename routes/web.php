<?php

use Illuminate\Support\Facades\Route;

// Auth
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;

// Pengurus
use App\Http\Controllers\Pengurus\DashboardController as PengurusDashboard;
use App\Http\Controllers\Pengurus\TransaksiController;
use App\Http\Controllers\Pengurus\KategoriController;
use App\Http\Controllers\Pengurus\LaporanController;
use App\Http\Controllers\Pengurus\SaldoAwalController;
use App\Http\Controllers\Pengurus\BackupController;
use App\Http\Controllers\Pengurus\NotifikasiController;
use App\Http\Controllers\Pengurus\AkunMasyarakatController;

// Kepala Desa
use App\Http\Controllers\KepalaDesa\DashboardController as KepalaDasaDashboard;
use App\Http\Controllers\KepalaDesa\LogTransaksiController;

// Masyarakat
use App\Http\Controllers\Masyarakat\DashboardController as MasyarakatDashboard;

// ============================================
// REDIRECT ROOT
// ============================================
Route::get('/', function () {
    if (auth()->check()) {
        return match (auth()->user()->role) {
            'pengurus'     => redirect()->route('pengurus.dashboard'),
            'kepala_desa'  => redirect()->route('kepaladesa.dashboard'),
            'masyarakat'   => redirect()->route('masyarakat.dashboard'),
            default        => redirect()->route('login'),
        };
    }
    return redirect()->route('login');
});

// ============================================
// AUTH ROUTES
// ============================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'login'])
        ->name('login.post');
});

Route::post('/logout', [LogoutController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ============================================
// PENGURUS ROUTES
// ============================================
Route::middleware(['auth', 'role:pengurus'])
    ->prefix('pengurus')
    ->name('pengurus.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [PengurusDashboard::class, 'index'])
            ->name('dashboard');

        // Saldo Awal
        Route::prefix('saldo-awal')
            ->name('saldo-awal.')
            ->group(function () {
                Route::get('/', [SaldoAwalController::class, 'index'])
                    ->name('index');
                Route::get('/create', [SaldoAwalController::class, 'create'])
                    ->name('create');
                Route::post('/store', [SaldoAwalController::class, 'store'])
                    ->name('store');
                Route::get('/edit/{id}', [SaldoAwalController::class, 'edit'])
                    ->name('edit');
                Route::put('/update/{id}', [SaldoAwalController::class, 'update'])
                    ->name('update');
            });

        // Kategori Transaksi
        Route::prefix('kategori')
            ->name('kategori.')
            ->group(function () {
                Route::get('/', [KategoriController::class, 'index'])
                    ->name('index');
                Route::get('/create', [KategoriController::class, 'create'])
                    ->name('create');
                Route::post('/store', [KategoriController::class, 'store'])
                    ->name('store');
                Route::get('/edit/{id}', [KategoriController::class, 'edit'])
                    ->name('edit');
                Route::put('/update/{id}', [KategoriController::class, 'update'])
                    ->name('update');
                Route::patch('/toggle/{id}', [KategoriController::class, 'toggle'])
                    ->name('toggle');
            });

        // Transaksi
        Route::prefix('transaksi')
            ->name('transaksi.')
            ->group(function () {
                Route::get('/', [TransaksiController::class, 'index'])
                    ->name('index');
                Route::get('/create', [TransaksiController::class, 'create'])
                    ->name('create');
                Route::post('/store', [TransaksiController::class, 'store'])
                    ->name('store');
                Route::get('/show/{id}', [TransaksiController::class, 'show'])
                    ->name('show');
                Route::get('/edit/{id}', [TransaksiController::class, 'edit'])
                    ->name('edit');
                Route::put('/update/{id}', [TransaksiController::class, 'update'])
                    ->name('update');
                Route::delete('/destroy/{id}', [TransaksiController::class, 'destroy'])
                    ->name('destroy');
            });

        // Laporan
        Route::prefix('laporan')
            ->name('laporan.')
            ->group(function () {
                Route::get('/', [LaporanController::class, 'index'])
                    ->name('index');
                Route::post('/generate', [LaporanController::class, 'generate'])
                    ->name('generate');
                Route::get('/download-pdf/{id}', [LaporanController::class, 'downloadPdf'])
                    ->name('download.pdf');
                Route::get('/download-excel/{id}', [LaporanController::class, 'downloadExcel'])
                    ->name('download.excel');
                Route::delete('/destroy/{id}', [LaporanController::class, 'destroy'])
                    ->name('destroy');
            });

        // Backup Data
        Route::prefix('backup')
            ->name('backup.')
            ->group(function () {
                Route::get('/', [BackupController::class, 'index'])
                    ->name('index');
                Route::post('/store', [BackupController::class, 'store'])
                    ->name('store');
                Route::get('/restore/{id}', [BackupController::class, 'restore'])
                    ->name('restore');
                Route::delete('/destroy/{id}', [BackupController::class, 'destroy'])
                    ->name('destroy');
            });

        // Notifikasi
        Route::prefix('notifikasi')
            ->name('notifikasi.')
            ->group(function () {
                Route::get('/', [NotifikasiController::class, 'index'])
                    ->name('index');
                Route::patch('/read/{id}', [NotifikasiController::class, 'read'])
                    ->name('read');
                Route::patch('/read-all', [NotifikasiController::class, 'readAll'])
                    ->name('read.all');
                Route::delete('/destroy/{id}', [NotifikasiController::class, 'destroy'])
                    ->name('destroy');
            });

        // Akun Masyarakat
        Route::prefix('akun-masyarakat')
            ->name('akun-masyarakat.')
            ->group(function () {
                Route::get('/', [AkunMasyarakatController::class, 'index'])
                    ->name('index');
                Route::get('/create', [AkunMasyarakatController::class, 'create'])
                    ->name('create');
                Route::post('/store', [AkunMasyarakatController::class, 'store'])
                    ->name('store');
                Route::get('/edit/{id}', [AkunMasyarakatController::class, 'edit'])
                    ->name('edit');
                Route::put('/update/{id}', [AkunMasyarakatController::class, 'update'])
                    ->name('update');
                Route::patch('/toggle/{id}', [AkunMasyarakatController::class, 'toggle'])
                    ->name('toggle');
            });
    });

// ============================================
// KEPALA DESA ROUTES
// ============================================
Route::middleware(['auth', 'role:kepala_desa'])
    ->prefix('kepala-desa')
    ->name('kepaladesa.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [KepalaDasaDashboard::class, 'index'])
            ->name('dashboard');

        // Log Transaksi
        Route::prefix('log-transaksi')
            ->name('log-transaksi.')
            ->group(function () {
                Route::get('/', [LogTransaksiController::class, 'index'])
                    ->name('index');
                Route::get('/show/{id}', [LogTransaksiController::class, 'show'])
                    ->name('show');
                Route::get('/filter', [LogTransaksiController::class, 'filter'])
                    ->name('filter');
            });
    });

// ============================================
// MASYARAKAT ROUTES
// ============================================
Route::middleware(['auth', 'role:masyarakat'])
    ->prefix('masyarakat')
    ->name('masyarakat.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [MasyarakatDashboard::class, 'index'])
            ->name('dashboard');
    });