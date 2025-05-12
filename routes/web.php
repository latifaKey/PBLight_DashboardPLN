<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\VerifikasiController;
use App\Http\Controllers\TahunPenilaianController;
use App\Http\Controllers\DataKinerjaController;
use App\Http\Controllers\TargetKinerjaController;
use App\Http\Controllers\EksporPdfController;
use App\Http\Controllers\RealisasiController;
use App\Http\Controllers\KPIController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\AktivitasLogController;
use Illuminate\Support\Facades\Auth;
use App\Models\Bidang;
use App\Models\Indikator;
use App\Models\NilaiKPI;

    // Redirect ke halaman utama
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // Halaman login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

    // Proses login
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Route dashboard yang membutuhkan autentikasi
    Route::middleware(['auth'])->group(function () {

        // Dashboard utama, setelah login diarahkan berdasarkan role
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Route lama tidak digunakan lagi karena sudah digabung di index()
        // Berikut ini menjadi alias saja untuk kompatibilitas
        Route::get('/dashboard/master', [DashboardController::class, 'master'])->name('dashboard.master');
        Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
        Route::get('/dashboard/user', [DashboardController::class, 'user'])->name('dashboard.user');

        // Dashboard lama untuk PIC admin per bidang sudah tidak digunakan lagi
        // (semua diarahkan ke dashboard.admin)

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

        Route::get('/dashboard/admin/sekretaris-perusahaan', function () {
            return view('dashboard.admin_sekretaris');
        })->name('dashboard.admin.sekretaris_perusahaan');

        Route::get('/dashboard/admin/hukum', function () {
            // Menampilkan dashboard hukum khusus
            $user = Auth::user();
            $tahun = request('tahun', date('Y'));
            $bulan = request('bulan', date('m'));
            $periodeTipe = request('periode_tipe', 'bulanan');

            // Dapatkan bidang hukum
            $bidang = Bidang::where('role_pic', 'pic_hukum')->first();

            if (!$bidang) {
                return redirect()->route('dashboard')->with('error', 'Bidang tidak ditemukan untuk PIC ini.');
            }

            // Dapatkan indikator untuk bidang hukum
            $indikators = Indikator::where('bidang_id', $bidang->id)
                ->where('aktif', true)
                ->orderBy('kode')
                ->get();

            // Dapatkan nilai KPI untuk indikator-indikator tersebut
            foreach ($indikators as $indikator) {
                $nilaiKPI = NilaiKPI::where('indikator_id', $indikator->id)
                    ->where('tahun', $tahun)
                    ->where('bulan', $bulan)
                    ->where('periode_tipe', $periodeTipe)
                    ->first();

                $indikator->nilai_persentase = $nilaiKPI ? $nilaiKPI->persentase : 0;
                $indikator->nilai_absolut = $nilaiKPI ? $nilaiKPI->nilai : 0;
                $indikator->diverifikasi = $nilaiKPI ? $nilaiKPI->diverifikasi : false;
            }

            // Hitung rata-rata nilai KPI untuk bidang ini
            $totalNilai = 0;
            foreach ($indikators as $indikator) {
                $totalNilai += $indikator->nilai_persentase;
            }

            $rataRata = $indikators->count() > 0 ? round($totalNilai / $indikators->count(), 2) : 0;

            // Tampilkan view khusus admin_hukum
            return view('dashboard.admin_hukum', compact('bidang', 'indikators', 'rataRata', 'tahun', 'bulan'));
        })->name('dashboard.admin.hukum');

        // Route untuk KPI
        Route::resource('kpi', KPIController::class);

        // Routes khusus KPI
        Route::middleware(['auth'])->prefix('kpi')->name('kpi.')->group(function () {
            // Verifikasi KPI
            Route::get('/verifikasi', [KPIController::class, 'verifikasi'])->name('verifikasi');
            Route::post('/verifikasi', [KPIController::class, 'prosesVerifikasi'])->name('prosesVerifikasi');

            // Laporan KPI
            Route::get('/laporan', [KPIController::class, 'laporan'])->name('laporan');

            // Riwayat KPI (redirect ke index untuk kompatibilitas)
            Route::get('/history', function() {
                return redirect()->route('kpi.index');
            })->name('history');

            Route::get('/history/export/excel', [KPIController::class, 'exportRiwayatToExcel'])->name('export.excel');
            Route::get('/history/export/pdf', [KPIController::class, 'exportRiwayatToPDF'])->name('export.pdf');
            Route::post('/history/finalisasi/{nilaiId}', [KPIController::class, 'finalisasiNilai'])->name('finalisasi');

            // Ini harus selalu di akhir karena ada parameter dinamis
            Route::get('/history/{indikatorId}', [KPIController::class, 'detailRiwayat'])->name('detail.riwayat');
        });

        // Resource controllers untuk fitur CRUD
        Route::resource('verifikasi', VerifikasiController::class);
        Route::resource('tahunPenilaian', TahunPenilaianController::class);
        Route::get('/tahunPenilaian/{id}/activate', [TahunPenilaianController::class, 'activate'])->name('tahunPenilaian.activate');
        Route::get('/tahunPenilaian/{id}/lock', [TahunPenilaianController::class, 'lock'])->name('tahunPenilaian.lock');
        Route::get('/tahunPenilaian/{id}/unlock', [TahunPenilaianController::class, 'unlock'])->name('tahunPenilaian.unlock');

        // Routes untuk Data Kinerja
        Route::get('/dataKinerja', [DataKinerjaController::class, 'index'])->name('dataKinerja.index');
        Route::get('/dataKinerja/pilar/{id?}', [DataKinerjaController::class, 'pilar'])->name('dataKinerja.pilar');
        Route::get('/dataKinerja/bidang/{id?}', [DataKinerjaController::class, 'bidang'])->name('dataKinerja.bidang');
        Route::get('/dataKinerja/indikator/{id}', [DataKinerjaController::class, 'indikator'])->name('dataKinerja.indikator');
        Route::get('/dataKinerja/perbandingan', [DataKinerjaController::class, 'perbandingan'])->name('dataKinerja.perbandingan');

        // Routes untuk Target Kinerja
        Route::get('/targetKinerja', [TargetKinerjaController::class, 'index'])->name('targetKinerja.index');
        Route::get('/targetKinerja/create', [TargetKinerjaController::class, 'create'])->name('targetKinerja.create');
        Route::post('/targetKinerja', [TargetKinerjaController::class, 'store'])->name('targetKinerja.store');
        Route::get('/targetKinerja/{targetKinerja}/edit', [TargetKinerjaController::class, 'edit'])->name('targetKinerja.edit');
        Route::put('/targetKinerja/{targetKinerja}', [TargetKinerjaController::class, 'update'])->name('targetKinerja.update');
        Route::get('/targetKinerja/{targetKinerja}/approve', [TargetKinerjaController::class, 'approve'])->name('targetKinerja.approve');
        Route::get('/targetKinerja/{targetKinerja}/unapprove', [TargetKinerjaController::class, 'unapprove'])->name('targetKinerja.unapprove');

        Route::resource('realisasi', RealisasiController::class);

        // Ekspor PDF
        Route::get('/ekspor-pdf', [EksporPdfController::class, 'index'])->name('eksporPdf.index');
        Route::post('/ekspor-pdf/bidang', [EksporPdfController::class, 'eksporBidang'])->name('eksporPdf.bidang');
        Route::post('/ekspor-pdf/pilar', [EksporPdfController::class, 'eksporPilar'])->name('eksporPdf.pilar');
        Route::post('/ekspor-pdf/keseluruhan', [EksporPdfController::class, 'eksporKeseluruhan'])->name('eksporPdf.keseluruhan');

        // CRUD Akun - sudah dikonfigurasi di controller
        Route::resource('akun', AkunController::class);

        // Notifikasi
        Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
        Route::get('/notifikasi/{id}/tandai-dibaca', [NotifikasiController::class, 'tandaiDibaca'])->name('notifikasi.tandaiDibaca');
        Route::post('/notifikasi/tandai-semua-dibaca', [NotifikasiController::class, 'tandaiSemuaDibaca'])->name('notifikasi.tandaiSemuaDibaca');
        Route::delete('/notifikasi/{id}', [NotifikasiController::class, 'destroy'])->name('notifikasi.destroy');
        Route::post('/notifikasi/hapus-sudah-dibaca', [NotifikasiController::class, 'hapusSudahDibaca'])->name('notifikasi.hapusSudahDibaca');
        Route::get('/notifikasi/jumlah-belum-dibaca', [NotifikasiController::class, 'getJumlahBelumDibaca'])->name('notifikasi.getJumlahBelumDibaca');
        Route::get('/notifikasi/terbaru', [NotifikasiController::class, 'getNotifikasiTerbaru'])->name('notifikasi.getNotifikasiTerbaru');

        // Log Aktivitas
        Route::prefix('aktivitas-log')->name('aktivitasLog.')->group(function () {
            Route::get('/', [AktivitasLogController::class, 'index'])->name('index');
            Route::get('/ekspor-csv', [AktivitasLogController::class, 'eksporCsv'])->name('eksporCsv');
            Route::post('/hapus-log-lama', [AktivitasLogController::class, 'hapusLogLama'])->name('hapusLogLama');
            Route::delete('/{id}', [AktivitasLogController::class, 'destroy'])->name('destroy');
            Route::post('/hapus-multiple', [AktivitasLogController::class, 'hapusMultiple'])->name('hapusMultiple');
            Route::get('/{id}', [AktivitasLogController::class, 'show'])->name('show')->where('id', '[0-9]+');
        });

        // ========== ROUTES UNTUK MASTER ADMIN (ASISTEN MANAJER) ==========
        Route::middleware(['auth'])->group(function () {
            // Middleware role sudah dihapus, karena pengecekan role dilakukan di controller

            // Verifikasi KPI
            Route::resource('verifikasi', VerifikasiController::class)->except(['create', 'edit', 'store']);
            Route::post('/verifikasi/massal', [VerifikasiController::class, 'verifikasiMassal'])->name('verifikasi.massal');

            // Tahun Penilaian
            Route::resource('tahunPenilaian', TahunPenilaianController::class);
            Route::get('/tahunPenilaian/{id}/activate', [TahunPenilaianController::class, 'activate'])->name('tahunPenilaian.activate');
            Route::get('/tahunPenilaian/{id}/lock', [TahunPenilaianController::class, 'lock'])->name('tahunPenilaian.lock');
            Route::get('/tahunPenilaian/{id}/unlock', [TahunPenilaianController::class, 'unlock'])->name('tahunPenilaian.unlock');

            // Ekspor PDF
            Route::get('/ekspor-pdf', [EksporPdfController::class, 'index'])->name('eksporPdf.index');
            Route::post('/ekspor-pdf/bidang', [EksporPdfController::class, 'eksporBidang'])->name('eksporPdf.bidang');
            Route::post('/ekspor-pdf/pilar', [EksporPdfController::class, 'eksporPilar'])->name('eksporPdf.pilar');
            Route::post('/ekspor-pdf/keseluruhan', [EksporPdfController::class, 'eksporKeseluruhan'])->name('eksporPdf.keseluruhan');

            // Log Aktivitas
            Route::prefix('aktivitas-log')->name('aktivitasLog.')->group(function () {
                Route::get('/', [AktivitasLogController::class, 'index'])->name('index');
                Route::get('/ekspor-csv', [AktivitasLogController::class, 'eksporCsv'])->name('eksporCsv');
                Route::post('/hapus-log-lama', [AktivitasLogController::class, 'hapusLogLama'])->name('hapusLogLama');
                Route::delete('/{id}', [AktivitasLogController::class, 'destroy'])->name('destroy');
                Route::post('/hapus-multiple', [AktivitasLogController::class, 'hapusMultiple'])->name('hapusMultiple');
                Route::get('/{id}', [AktivitasLogController::class, 'show'])->name('show')->where('id', '[0-9]+');
            });
        });

        // ========== ROUTES UNTUK ADMIN (PIC BIDANG) ==========
        Route::middleware(['auth'])->group(function () {
            // Manajemen KPI Bidang - tambahkan pengecekan role di controller sebagai ganti middleware
            Route::resource('realisasi', RealisasiController::class)->except(['destroy']);

            // Data Kinerja Bidang - tambahkan pengecekan role di controller sebagai ganti middleware
            Route::resource('dataKinerja', DataKinerjaController::class)->except(['destroy']);
        });

        // ========== ROUTES UNTUK SEMUA (ADMIN & KARYAWAN) ==========
        // Semua routes KPI sudah diatur di atas dengan Resource dan Group
        // Hapus semua duplikasi route KPI di bagian ini
    });

    // ========== DASHBOARD LEGACY (DEPRECATED) ==========
    // Dashboard admin lama untuk backward compatibility
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

    Route::get('/dashboard/admin/sekretaris-perusahaan', function () {
        return view('dashboard.admin_sekretaris');
    })->name('dashboard.admin.sekretaris_perusahaan');

    Route::get('/dashboard/admin/hukum', function () {
        // Menampilkan dashboard hukum khusus
        $user = Auth::user();
        $tahun = request('tahun', date('Y'));
        $bulan = request('bulan', date('m'));
        $periodeTipe = request('periode_tipe', 'bulanan');

        // Dapatkan bidang hukum
        $bidang = Bidang::where('role_pic', 'pic_hukum')->first();

        if (!$bidang) {
            return redirect()->route('dashboard')->with('error', 'Bidang tidak ditemukan untuk PIC ini.');
        }

        // Dapatkan indikator untuk bidang hukum
        $indikators = Indikator::where('bidang_id', $bidang->id)
            ->where('aktif', true)
            ->orderBy('kode')
            ->get();

        // Dapatkan nilai KPI untuk indikator-indikator tersebut
        foreach ($indikators as $indikator) {
            $nilaiKPI = NilaiKPI::where('indikator_id', $indikator->id)
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->where('periode_tipe', $periodeTipe)
                ->first();

            $indikator->nilai_persentase = $nilaiKPI ? $nilaiKPI->persentase : 0;
            $indikator->nilai_absolut = $nilaiKPI ? $nilaiKPI->nilai : 0;
            $indikator->diverifikasi = $nilaiKPI ? $nilaiKPI->diverifikasi : false;
        }

        // Hitung rata-rata nilai KPI untuk bidang ini
        $totalNilai = 0;
        foreach ($indikators as $indikator) {
            $totalNilai += $indikator->nilai_persentase;
        }

        $rataRata = $indikators->count() > 0 ? round($totalNilai / $indikators->count(), 2) : 0;

        // Tampilkan view khusus admin_hukum
        return view('dashboard.admin_hukum', compact('bidang', 'indikators', 'rataRata', 'tahun', 'bulan'));
    })->name('dashboard.admin.hukum');

