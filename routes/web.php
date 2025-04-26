<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\VerifikasiController;
use App\Http\Controllers\TahunPenilaianController;
use App\Http\Controllers\DataKinerjaController;
use App\Http\Controllers\EksporPdfController;
use App\Http\Controllers\RealisasiController;

    // Redirect ke halaman utama
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // Halaman login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

    // Proses login
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Route dashboard yang membutuhkan autentikasi
    Route::middleware(['auth'])->group(function () {

        // Dashboard utama, setelah login diarahkan berdasarkan role
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Dashboard untuk master_admin (asisten_manager)
        Route::get('/dashboard/master', [DashboardController::class, 'master'])->name('dashboard.master');

        // Dashboard umum untuk admin (PIC)
        Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');

        // Dashboard untuk PIC admin per bidang
        Route::get('/dashboard/admin/keuangan', function () {
            return view('dashboard.admin_keuangan');
        })->name('dashboard.admin.keuangan');

        Route::get('/dashboard/admin/risiko', function () {
            return view('dashboard.admin_risiko');
        })->name('dashboard.admin.risiko');

        Route::get('/dashboard/admin/skreperusahaan', function () {
            return view('dashboard.admin_skreperusahaan');
        })->name('dashboard.admin.skreperusahaan');

        Route::get('/dashboard/admin/perencanaan-operasi', function () {
            return view('dashboard.admin_perencanaan_operasi');
        })->name('dashboard.admin.perencanaan_operasi');

        Route::get('/dashboard/admin/pengembangan-bisnis', function () {
            return view('dashboard.admin_pengembangan_bisnis');
        })->name('dashboard.admin.pengembangan_bisnis');

        Route::get('/dashboard/admin/human-capital', function () {
            return view('dashboard.admin_human_capital');
        })->name('dashboard.admin.human_capital');

        Route::get('/dashboard/admin/k3l', function () {
            return view('dashboard.admin_k3l');
        })->name('dashboard.admin.k3l');

        Route::get('/dashboard/admin/perencanaan-korporat', function () {
            return view('dashboard.admin_perencanaan_korporat');
        })->name('dashboard.admin.perencanaan_korporat');

        // Dashboard untuk karyawan biasa
        Route::get('/dashboard/user', [DashboardController::class, 'user'])->name('dashboard.user');

        // Resource controllers untuk fitur CRUD
        Route::resource('verifikasi', VerifikasiController::class);
        Route::resource('tahunPenilaian', TahunPenilaianController::class);
        Route::resource('dataKinerja', DataKinerjaController::class);
        Route::resource('realisasi', RealisasiController::class);

        // Ekspor PDF
        Route::get('/ekspor-pdf', [EksporPdfController::class, 'index'])->name('eksporPdf.index');

        // CRUD Akun (bisa kasih middleware khusus nanti, misal hanya untuk asisten manager)
        Route::get('/akun', [AkunController::class, 'index'])->name('akun.index');
        Route::get('/akun/create', [AkunController::class, 'create'])->name('akun.create');
        Route::post('/akun', [AkunController::class, 'store'])->name('akun.store');
        Route::get('/akun/{id}', [AkunController::class, 'show'])->name('akun.show');
        Route::get('/akun/{id}/edit', [AkunController::class, 'edit'])->name('akun.edit');
        Route::put('/akun/{id}', [AkunController::class, 'update'])->name('akun.update');
        Route::delete('/akun/{id}', [AkunController::class, 'destroy'])->name('akun.destroy');

    });

