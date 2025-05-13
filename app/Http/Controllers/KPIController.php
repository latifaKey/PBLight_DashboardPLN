<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pilar;
use App\Models\Indikator;
use App\Models\NilaiKPI;
use App\Models\Bidang;
use App\Models\Notifikasi;
use App\Models\AktivitasLog;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;

class KPIController extends Controller
{
    /**
     * Validasi akses berdasarkan role
     */
    private function validateAccessForAdmin($bidangId)
    {
        $user = Auth::user();

        // Jika master admin, selalu diizinkan
        if ($user->isMasterAdmin()) {
            return true;
        }

        // Jika admin, validasi akses ke bidangnya
        if ($user->isAdmin()) {
            $bidang = $user->getBidang();
            if (!$bidang || $bidang->id != $bidangId) {
                return false;
            }
            return true;
        }

        // Karyawan biasa tidak boleh mengakses
        return false;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $tahun = $request->tahun ?? date('Y');
        $bulan = $request->bulan ?? date('m');
        $periodeTipe = $request->periode_tipe ?? 'bulanan';

        // Jika master admin, dapatkan semua pilar dan indikator
        if ($user->isMasterAdmin()) {
            $pilars = Pilar::with(['indikators' => function($query) {
                $query->where('aktif', true)->orderBy('kode');
            }])->orderBy('urutan')->get();

            // Dapatkan nilai untuk indikator
            foreach ($pilars as $pilar) {
                foreach ($pilar->indikators as $indikator) {
                    $nilaiKPI = NilaiKPI::where('indikator_id', $indikator->id)
                        ->where('tahun', $tahun)
                        ->where('bulan', $bulan)
                        ->where('periode_tipe', $periodeTipe)
                        ->first();

                    $indikator->nilai_persentase = $nilaiKPI ? $nilaiKPI->persentase : 0;
                    $indikator->nilai_absolut = $nilaiKPI ? $nilaiKPI->nilai : 0;
                    $indikator->diverifikasi = $nilaiKPI ? $nilaiKPI->diverifikasi : false;
                }
            }

            return view('kpi.index', compact('pilars', 'tahun', 'bulan', 'periodeTipe', 'request'));
        }

        // Jika admin, dapatkan indikator untuk bidangnya
        if ($user->isAdmin()) {
            $bidang = $user->getBidang();

            if (!$bidang) {
                return redirect()->route('dashboard')->with('error', 'Bidang tidak ditemukan untuk PIC ini.');
            }

            $indikators = Indikator::where('bidang_id', $bidang->id)
                ->where('aktif', true)
                ->orderBy('kode')
                ->get();

            // Dapatkan nilai untuk indikator
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

            return view('kpi.admin_index', compact('bidang', 'indikators', 'tahun', 'bulan', 'periodeTipe', 'request'));
        }

        // Jika karyawan, dapatkan ringkasan
        $bidangs = Bidang::orderBy('nama')->get();
        $bidangData = [];

        foreach ($bidangs as $bidang) {
            $indikators = Indikator::where('bidang_id', $bidang->id)
                ->where('aktif', true)
                ->get();

            $totalNilai = 0;
            foreach ($indikators as $indikator) {
                $nilaiKPI = NilaiKPI::where('indikator_id', $indikator->id)
                    ->where('tahun', $tahun)
                    ->where('bulan', $bulan)
                    ->where('periode_tipe', $periodeTipe)
                    ->first();

                $totalNilai += $nilaiKPI ? $nilaiKPI->persentase : 0;
            }

            $rataRata = $indikators->count() > 0 ? round($totalNilai / $indikators->count(), 2) : 0;

            $bidangData[] = [
                'nama' => $bidang->nama,
                'nilai' => $rataRata,
            ];
        }

        // Pastikan bidangData selalu berupa array biasa, bukan collection
        if (!is_array($bidangData)) {
            $bidangData = is_object($bidangData) && method_exists($bidangData, 'toArray') ?
                $bidangData->toArray() : (array)$bidangData;
        }

        return view('kpi.user_index', compact('bidangData', 'tahun', 'bulan', 'periodeTipe'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        // Hanya admin dan master admin yang boleh create
        if (!$user->isAdmin() && !$user->isMasterAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke fitur ini.');
        }

        $tahun = $request->tahun ?? date('Y');
        $bulan = $request->bulan ?? date('m');
        $periodeTipe = $request->periode_tipe ?? 'bulanan';
        $indikatorId = $request->indikator_id;

        // Jika ada indikator yang dipilih
        if ($indikatorId) {
            $indikator = Indikator::findOrFail($indikatorId);

            // Validasi akses admin ke bidang
            if ($user->isAdmin()) {
                $bidang = $user->getBidang();
                if (!$bidang || $bidang->id != $indikator->bidang_id) {
                    return redirect()->route('kpi.index')->with('error', 'Anda tidak memiliki akses untuk mengelola indikator ini.');
                }
            }

            return view('kpi.create', compact('indikator', 'tahun', 'bulan', 'periodeTipe'));
        }

        // Jika master admin
        if ($user->isMasterAdmin()) {
            $indikators = Indikator::where('aktif', true)->orderBy('kode')->get();
            return view('kpi.select_indikator', compact('indikators', 'tahun', 'bulan', 'periodeTipe'));
        }

        // Jika admin
        $bidang = $user->getBidang();
        if (!$bidang) {
            return redirect()->route('dashboard')->with('error', 'Bidang tidak ditemukan untuk PIC ini.');
        }

        $indikators = Indikator::where('bidang_id', $bidang->id)
            ->where('aktif', true)
            ->orderBy('kode')
            ->get();

        return view('kpi.select_indikator', compact('indikators', 'tahun', 'bulan', 'periodeTipe'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Hanya admin dan master admin yang boleh store
        if (!$user->isAdmin() && !$user->isMasterAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke fitur ini.');
        }

        $request->validate([
            'indikator_id' => 'required|exists:indikators,id',
            'nilai' => 'required|numeric|min:0',
            'tahun' => 'required|integer|min:2000|max:2100',
            'bulan' => 'required|integer|min:1|max:12',
            'periode_tipe' => 'required|in:bulanan,mingguan',
            'minggu' => 'nullable|integer|min:1|max:5',
            'keterangan' => 'nullable|string',
        ]);

        $indikator = Indikator::find($request->indikator_id);

        // Validasi akses admin ke bidang
        if ($user->isAdmin()) {
            $bidang = $user->getBidang();
            if (!$bidang || $bidang->id != $indikator->bidang_id) {
                return redirect()->route('kpi.index')->with('error', 'Anda tidak memiliki akses untuk mengelola indikator ini.');
            }
        }

        $persentase = min(100, ($request->nilai / $indikator->target) * 100);

        // Cek apakah sudah ada nilai untuk periode ini
        $existing = NilaiKPI::where('indikator_id', $request->indikator_id)
            ->where('tahun', $request->tahun)
            ->where('bulan', $request->bulan)
            ->where('periode_tipe', $request->periode_tipe);

        if ($request->periode_tipe == 'mingguan' && $request->minggu) {
            $existing->where('minggu', $request->minggu);
        } else {
            $existing->whereNull('minggu');
        }

        $existingData = $existing->first();

        if ($existingData) {
            // Update nilai yang sudah ada
            $existingData->update([
                'nilai' => $request->nilai,
                'persentase' => $persentase,
                'keterangan' => $request->keterangan,
                'user_id' => $user->id,
                'diverifikasi' => false,
                'verifikasi_oleh' => null,
                'verifikasi_pada' => null,
            ]);

            // Log aktivitas
            AktivitasLog::log(
                $user,
                'update',
                'Mengupdate nilai KPI ' . $indikator->kode . ' - ' . $indikator->nama,
                [
                    'indikator_id' => $indikator->id,
                    'nilai_lama' => $existingData->getOriginal('nilai'),
                    'nilai_baru' => $request->nilai,
                    'tahun' => $request->tahun,
                    'bulan' => $request->bulan,
                ],
                $request->ip(),
                $request->userAgent()
            );

            // Notifikasi ke asisten manager
            Notifikasi::kirimKeMasterAdmin(
                'Update Nilai KPI',
                'Nilai KPI ' . $indikator->kode . ' - ' . $indikator->nama . ' telah diperbarui oleh ' . $user->name,
                'warning',
                route('kpi.verifikasi')
            );

            return redirect()->route('kpi.index', [
                'tahun' => $request->tahun,
                'bulan' => $request->bulan,
                'periode_tipe' => $request->periode_tipe
            ])->with('success', 'Nilai KPI berhasil diperbarui.');
        } else {
            // Buat nilai baru
            $nilaiKPI = NilaiKPI::create([
                'indikator_id' => $request->indikator_id,
                'user_id' => $user->id,
                'tahun' => $request->tahun,
                'bulan' => $request->bulan,
                'minggu' => $request->minggu,
                'periode_tipe' => $request->periode_tipe,
                'nilai' => $request->nilai,
                'persentase' => $persentase,
                'keterangan' => $request->keterangan,
                'diverifikasi' => false,
            ]);

            // Log aktivitas
            AktivitasLog::log(
                $user,
                'create',
                'Menambahkan nilai KPI ' . $indikator->kode . ' - ' . $indikator->nama,
                [
                    'indikator_id' => $indikator->id,
                    'nilai' => $request->nilai,
                    'tahun' => $request->tahun,
                    'bulan' => $request->bulan,
                ],
                $request->ip(),
                $request->userAgent()
            );

            // Notifikasi ke asisten manager
            Notifikasi::kirimKeMasterAdmin(
                'Tambah Nilai KPI Baru',
                'Nilai KPI ' . $indikator->kode . ' - ' . $indikator->nama . ' telah ditambahkan oleh ' . $user->name,
                'info',
                route('kpi.verifikasi')
            );

            return redirect()->route('kpi.index', [
                'tahun' => $request->tahun,
                'bulan' => $request->bulan,
                'periode_tipe' => $request->periode_tipe
            ])->with('success', 'Nilai KPI berhasil disimpan.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $indikator = Indikator::findOrFail($id);
        $tahun = request('tahun', date('Y'));

        // Dapatkan data historis
        $nilaiKPIs = NilaiKPI::where('indikator_id', $id)
            ->where('tahun', $tahun)
            ->orderBy('bulan')
            ->orderBy('minggu')
            ->get();

        // Kelompokkan berdasarkan periode
        $bulananData = $nilaiKPIs->where('periode_tipe', 'bulanan')->keyBy('bulan');
        $mingguanData = $nilaiKPIs->where('periode_tipe', 'mingguan')->groupBy('bulan');

        return view('kpi.show', compact('indikator', 'bulananData', 'mingguanData', 'tahun'));
    }

    /**
     * Menampilkan form untuk verifikasi KPI (untuk master admin)
     */
    public function verifikasi()
    {
        $user = Auth::user();

        if (!$user->isMasterAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke fitur ini.');
        }

        $tahun = request('tahun', date('Y'));
        $bulan = request('bulan', date('m'));
        $bidangId = request('bidang_id');

        $query = NilaiKPI::with(['indikator.bidang', 'indikator.pilar', 'user'])
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->where('diverifikasi', false);

        if ($bidangId) {
            $query->whereHas('indikator', function($q) use ($bidangId) {
                $q->where('bidang_id', $bidangId);
            });
        }

        $nilaiKPIs = $query->get();
        $bidangs = Bidang::all();

        return view('kpi.verifikasi', compact('nilaiKPIs', 'bidangs', 'tahun', 'bulan', 'bidangId'));
    }

    /**
     * Proses verifikasi KPI
     */
    public function prosesVerifikasi(Request $request)
    {
        $user = Auth::user();

        if (!$user->isMasterAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke fitur ini.');
        }

        $request->validate([
            'nilai_ids' => 'required|array',
            'nilai_ids.*' => 'exists:nilai_kpi,id',
        ]);

        $nilaiKPIs = NilaiKPI::with('indikator')->whereIn('id', $request->nilai_ids)->get();

        foreach ($nilaiKPIs as $nilaiKPI) {
            $nilaiKPI->update([
                'diverifikasi' => true,
                'verifikasi_oleh' => $user->id,
                'verifikasi_pada' => Carbon::now(),
            ]);

            // Log aktivitas
            AktivitasLog::log(
                $user,
                'verify',
                'Memverifikasi nilai KPI ' . $nilaiKPI->indikator->kode . ' - ' . $nilaiKPI->indikator->nama,
                [
                    'indikator_id' => $nilaiKPI->indikator_id,
                    'nilai' => $nilaiKPI->nilai,
                    'tahun' => $nilaiKPI->tahun,
                    'bulan' => $nilaiKPI->bulan,
                ],
                $request->ip(),
                $request->userAgent()
            );

            // Kirim notifikasi ke user yang input
            Notifikasi::create([
                'user_id' => $nilaiKPI->user_id,
                'judul' => 'KPI Terverifikasi',
                'pesan' => 'Nilai KPI ' . $nilaiKPI->indikator->kode . ' - ' . $nilaiKPI->indikator->nama . ' telah diverifikasi oleh ' . $user->name,
                'jenis' => 'success',
                'dibaca' => false,
                'url' => route('kpi.show', $nilaiKPI->indikator_id),
            ]);
        }

        return redirect()->back()->with('success', count($nilaiKPIs) . ' Nilai KPI berhasil diverifikasi.');
    }

    /**
     * Menampilkan history KPI
     */
    public function history(Request $request)
    {
        $user = Auth::user();
        $tahun = $request->tahun ?? date('Y');
        $indikatorId = $request->indikator_id;

        // Simpan dalam histori pencarian
        $this->logHistoriPencarian($user, 'history_kpi', 'Mengakses riwayat KPI tahun ' . $tahun);

        // Jika ada indikator spesifik yang diminta
        if ($indikatorId) {
            return $this->detailRiwayat($request, $indikatorId);
        }

        if ($user->isMasterAdmin()) {
            // Master admin bisa melihat semua data
            $pilars = Pilar::with(['indikators' => function($query) {
                $query->where('aktif', true)->orderBy('kode');
            }])->orderBy('urutan')->get();

            foreach ($pilars as $pilar) {
                foreach ($pilar->indikators as $indikator) {
                    $indikator->historiData = collect($this->getIndikatorHistoriData($indikator->id, $tahun));
                }
            }

            // Tambahkan statistics data untuk dashboard
            $statistics = $this->getHistoryStatistics($pilars, $tahun);

            // Pastikan tidak ada collection di dalam statistics untuk mencegah error
            $statistics = $this->ensureStatisticsArray($statistics);

            return view('kpi.history', compact('pilars', 'tahun', 'statistics', 'request'));
        } elseif ($user->isAdmin()) {
            // Admin hanya bisa melihat data bidangnya
            $bidang = $user->getBidang();

            if (!$bidang) {
                return redirect()->route('dashboard')->with('error', 'Bidang tidak ditemukan untuk PIC ini.');
            }

            $indikators = Indikator::where('bidang_id', $bidang->id)
                ->where('aktif', true)
                ->orderBy('kode')
                ->get();

            foreach ($indikators as $indikator) {
                $indikator->historiData = collect($this->getIndikatorHistoriData($indikator->id, $tahun));
            }

            // Tambahkan statistics data untuk dashboard
            $statistics = $this->getIndikatorStatistics($indikators, $tahun, $bidang->nama);

            // Pastikan tidak ada collection di dalam statistics untuk mencegah error
            $statistics = $this->ensureStatisticsArray($statistics);

            // Buat struktur data yang sama dengan view master admin
            $pilars = collect([
                (object)[
                    'kode' => $bidang->kode ?? 'BD',
                    'nama' => $bidang->nama,
                    'indikators' => $indikators
                ]
            ]);

            return view('kpi.history', compact('pilars', 'tahun', 'statistics', 'request'));
        } else {
            // Karyawan hanya bisa melihat ringkasan
            $bidangs = Bidang::all();
            $pilars = collect([]);

            foreach ($bidangs as $bidang) {
                $indikators = Indikator::where('bidang_id', $bidang->id)
                    ->where('aktif', true)
                    ->orderBy('kode')
                    ->get();

                foreach ($indikators as $indikator) {
                    $indikator->historiData = collect($this->getIndikatorHistoriData($indikator->id, $tahun));
                }

                // Tambahkan sebagai pilar agar struktur data konsisten dengan view
                if ($indikators->isNotEmpty()) {
                    $pilars->push((object)[
                        'kode' => $bidang->kode ?? 'BD',
                        'nama' => $bidang->nama,
                        'indikators' => $indikators
                    ]);
                }
            }

            // Tambahkan statistics data untuk dashboard
            // Ubah dari array menjadi collection agar bisa menggunakan fungsi avg()
            $statistics = $this->getAllBidangStatistics($pilars, $tahun);

            // Pastikan tidak ada collection di dalam statistics untuk mencegah error
            $statistics = $this->ensureStatisticsArray($statistics);

            return view('kpi.history', compact('pilars', 'tahun', 'statistics', 'request'));
        }
    }

    /**
     * Menghitung statistik untuk data historis KPI
     */
    private function getHistoryStatistics($pilars, $tahun)
    {
        $totalIndikator = 0;
        $totalTercapai = 0;
        $totalDiverifikasi = 0;
        $performaRatarata = 0;
        $totalNilai = 0;
        $totalData = 0;

        foreach ($pilars as $pilar) {
            foreach ($pilar->indikators as $indikator) {
                $totalIndikator++;
                $indikatorTercapai = false;
                $indikatorNilai = 0;
                $indikatorCount = 0;

                // Pastikan historiData adalah collection
                $historiData = $indikator->historiData;
                if (!is_object($historiData) || !method_exists($historiData, 'avg')) {
                    $historiData = collect($historiData);
                    $indikator->historiData = $historiData;
                }

                foreach ($historiData as $data) {
                    if ($data['nilai'] > 0) {
                        $indikatorNilai += $data['nilai'];
                        $indikatorCount++;
                        $totalData++;

                        if ($data['nilai'] >= 80) {
                            $indikatorTercapai = true;
                        }

                        if ($data['diverifikasi']) {
                            $totalDiverifikasi++;
                        }

                        $totalNilai += $data['nilai'];
                    }
                }

                if ($indikatorTercapai) {
                    $totalTercapai++;
                }
            }
        }

        $performaRatarata = $totalData > 0 ? round($totalNilai / $totalData, 2) : 0;
        $persentaseTercapai = $totalIndikator > 0 ? round(($totalTercapai / $totalIndikator) * 100, 2) : 0;
        $persentaseDiverifikasi = $totalData > 0 ? round(($totalDiverifikasi / $totalData) * 100, 2) : 0;

        return [
            'total_indikator' => $totalIndikator,
            'indikator_tercapai' => $totalTercapai,
            'persentase_tercapai' => $persentaseTercapai,
            'persentase_diverifikasi' => $persentaseDiverifikasi,
            'performa_ratarata' => $performaRatarata,
        ];
    }

    /**
     * Menghitung statistik untuk data historis KPI per bidang
     */
    private function getIndikatorStatistics($indikators, $tahun, $bidangNama)
    {
        // Pastikan indikators adalah collection, bukan array
        if (is_array($indikators)) {
            $indikators = collect($indikators);
        }

        $totalIndikator = $indikators->count();
        $totalTercapai = 0;
        $totalDiverifikasi = 0;
        $performaRatarata = 0;
        $totalNilai = 0;
        $totalData = 0;

        foreach ($indikators as $indikator) {
            $indikatorTercapai = false;

            // Pastikan historiData adalah collection
            $historiData = $indikator->historiData;
            if (!is_object($historiData) || !method_exists($historiData, 'avg')) {
                $historiData = collect($historiData);
                $indikator->historiData = $historiData;
            }

            foreach ($historiData as $data) {
                if ($data['nilai'] > 0) {
                    $totalNilai += $data['nilai'];
                    $totalData++;

                    if ($data['nilai'] >= 80) {
                        $indikatorTercapai = true;
                    }

                    if ($data['diverifikasi']) {
                        $totalDiverifikasi++;
                    }
                }
            }

            if ($indikatorTercapai) {
                $totalTercapai++;
            }
        }

        $performaRatarata = $totalData > 0 ? round($totalNilai / $totalData, 2) : 0;
        $persentaseTercapai = $totalIndikator > 0 ? round(($totalTercapai / $totalIndikator) * 100, 2) : 0;
        $persentaseDiverifikasi = $totalData > 0 ? round(($totalDiverifikasi / $totalData) * 100, 2) : 0;

        return [
            'bidang' => $bidangNama,
            'total_indikator' => $totalIndikator,
            'indikator_tercapai' => $totalTercapai,
            'persentase_tercapai' => $persentaseTercapai,
            'persentase_diverifikasi' => $persentaseDiverifikasi,
            'performa_ratarata' => $performaRatarata,
        ];
    }

    /**
     * Menghitung statistik untuk semua bidang
     */
    private function getAllBidangStatistics($pilars, $tahun)
    {
        $statistik = collect([]); // Ubah array menjadi collection
        $totalIndikator = 0;
        $totalTercapai = 0;
        $totalDiverifikasi = 0;
        $totalNilai = 0;
        $totalData = 0;

        foreach ($pilars as $pilar) {
            // Pastikan indikators adalah collection, bukan array
            if (is_array($pilar->indikators)) {
                $pilar->indikators = collect($pilar->indikators);
            }

            $bidangStat = $this->getIndikatorStatistics($pilar->indikators, $tahun, $pilar->nama);
            $statistik->push($bidangStat); // Push ke collection bukan array

            $totalIndikator += $bidangStat['total_indikator'];
            $totalTercapai += $bidangStat['indikator_tercapai'];
            $totalNilai += ($bidangStat['performa_ratarata'] * $bidangStat['total_indikator']);
            $totalData += $bidangStat['total_indikator'];
            $totalDiverifikasi += ($bidangStat['persentase_diverifikasi'] * $bidangStat['total_indikator'] / 100);
        }

        $performaRatarata = $totalData > 0 ? round($totalNilai / $totalData, 2) : 0;
        $persentaseTercapai = $totalIndikator > 0 ? round(($totalTercapai / $totalIndikator) * 100, 2) : 0;
        $persentaseDiverifikasi = $totalIndikator > 0 ? round(($totalDiverifikasi / $totalIndikator) * 100, 2) : 0;

        // Ubah hasil menjadi array asosiatif untuk diakses di view
        return [
            'bidang_stats' => $statistik->toArray(), // Convert collection ke array
            'total_indikator' => $totalIndikator,
            'indikator_tercapai' => $totalTercapai,
            'persentase_tercapai' => $persentaseTercapai,
            'persentase_diverifikasi' => $persentaseDiverifikasi,
            'performa_ratarata' => $performaRatarata,
        ];
    }

    /**
     * Mencatat histori pencarian
     */
    private function logHistoriPencarian($user, $tipe, $deskripsi)
    {
        // Catat log aktivitas
        AktivitasLog::log(
            $user,
            'view',
            $deskripsi,
            'Mengakses halaman ' . $tipe,
            null,
            [
                'tipe' => $tipe,
                'parameter' => request()->all()
            ],
            request()->ip(),
            request()->userAgent()
        );
    }

    /**
     * Helper method untuk mendapatkan data historis indikator
     */
    private function getIndikatorHistoriData($indikatorId, $tahun)
    {
        $data = [];

        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $nilaiKPI = NilaiKPI::where('indikator_id', $indikatorId)
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->where('periode_tipe', 'bulanan')
                ->first();

            $data[] = [
                'nilai' => $nilaiKPI ? $nilaiKPI->persentase : 0,
                'diverifikasi' => $nilaiKPI ? $nilaiKPI->diverifikasi : false,
            ];
        }

        return $data; // Data akan diubah menjadi collection pada tempat penggunaan
    }

    /**
     * Menampilkan laporan KPI
     */
    public function laporan(Request $request)
    {
        $user = Auth::user();
        $tahun = $request->tahun ?? date('Y');
        $bulan = $request->bulan ?? date('m');

        if ($user->isMasterAdmin()) {
            // Master admin bisa melihat semua data
            $pilars = Pilar::with(['indikators' => function($query) {
                $query->where('aktif', true)->orderBy('kode');
            }])->orderBy('urutan')->get();

            foreach ($pilars as $pilar) {
                foreach ($pilar->indikators as $indikator) {
                    $nilaiKPI = NilaiKPI::where('indikator_id', $indikator->id)
                        ->where('tahun', $tahun)
                        ->where('bulan', $bulan)
                        ->where('periode_tipe', 'bulanan')
                        ->first();

                    $indikator->nilai_persentase = $nilaiKPI ? $nilaiKPI->persentase : 0;
                    $indikator->nilai_absolut = $nilaiKPI ? $nilaiKPI->nilai : 0;
                    $indikator->diverifikasi = $nilaiKPI ? $nilaiKPI->diverifikasi : false;
                }
            }

            return view('kpi.laporan', compact('pilars', 'tahun', 'bulan'));
        } elseif ($user->isAdmin()) {
            // Admin hanya bisa melihat data bidangnya
            $bidang = $user->getBidang();

            if (!$bidang) {
                return redirect()->route('dashboard')->with('error', 'Bidang tidak ditemukan untuk PIC ini.');
            }

            $indikators = Indikator::where('bidang_id', $bidang->id)
                ->where('aktif', true)
                ->orderBy('kode')
                ->get();

            foreach ($indikators as $indikator) {
                $nilaiKPI = NilaiKPI::where('indikator_id', $indikator->id)
                    ->where('tahun', $tahun)
                    ->where('bulan', $bulan)
                    ->where('periode_tipe', 'bulanan')
                    ->first();

                $indikator->nilai_persentase = $nilaiKPI ? $nilaiKPI->persentase : 0;
                $indikator->nilai_absolut = $nilaiKPI ? $nilaiKPI->nilai : 0;
                $indikator->diverifikasi = $nilaiKPI ? $nilaiKPI->diverifikasi : false;
            }

            return view('kpi.laporan_admin', compact('bidang', 'indikators', 'tahun', 'bulan'));
        } else {
            // Karyawan hanya bisa melihat ringkasan
            $bidangs = Bidang::all();
            $bidangData = [];

            foreach ($bidangs as $bidang) {
                $indikators = Indikator::where('bidang_id', $bidang->id)
                    ->where('aktif', true)
                    ->get();

                $totalNilai = 0;
                foreach ($indikators as $indikator) {
                    $nilaiKPI = NilaiKPI::where('indikator_id', $indikator->id)
                        ->where('tahun', $tahun)
                        ->where('bulan', $bulan)
                        ->where('periode_tipe', 'bulanan')
                        ->first();

                    $totalNilai += $nilaiKPI ? $nilaiKPI->persentase : 0;
                }

                $rataRata = $indikators->count() > 0 ? round($totalNilai / $indikators->count(), 2) : 0;

                $bidangData[] = [
                    'nama' => $bidang->nama,
                    'nilai' => $rataRata,
                ];
            }

            return view('kpi.laporan_user', compact('bidangData', 'tahun', 'bulan'));
        }
    }

    /**
     * Ekspor data Riwayat KPI ke Excel
     */
    public function exportRiwayatToExcel(Request $request)
    {
        $user = Auth::user();
        $tahun = $request->tahun ?? date('Y');

        // Log aktivitas ekspor
        AktivitasLog::log(
            $user,
            'export',
            'Mengekspor data riwayat KPI ' . $tahun . ' ke Excel',
            'Ekspor data ke Excel',
            null,
            [
                'tahun' => $tahun,
                'format' => 'excel',
                'filter' => $request->all()
            ],
            $request->ip(),
            $request->userAgent()
        );

        // Logika untuk mengambil data sesuai dengan halaman history()
        if ($user->isMasterAdmin()) {
            $pilars = Pilar::with(['indikators' => function($query) {
                $query->where('aktif', true)->orderBy('kode');
            }])->orderBy('urutan')->get();

            foreach ($pilars as $pilar) {
                foreach ($pilar->indikators as $indikator) {
                    $indikator->historiData = $this->getIndikatorHistoriData($indikator->id, $tahun);
                }
            }

            // Output ke excel (contoh implementasi sederhana)
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diekspor',
                'tahun' => $tahun
            ]);
        }

        // Jika bukan master admin, redirect ke halaman riwayat
        return redirect()->route('kpi.history')->with('error', 'Anda tidak memiliki akses untuk mengekspor data ini');
    }

    /**
     * Ekspor data Riwayat KPI ke PDF
     */
    public function exportRiwayatToPDF(Request $request)
    {
        $user = Auth::user();
        $tahun = $request->tahun ?? date('Y');

        // Log aktivitas ekspor
        AktivitasLog::log(
            $user,
            'export',
            'Mengekspor data riwayat KPI ' . $tahun . ' ke PDF',
            'Ekspor data ke PDF',
            null,
            [
                'tahun' => $tahun,
                'format' => 'pdf',
                'filter' => $request->all()
            ],
            $request->ip(),
            $request->userAgent()
        );

        // Logika untuk mengambil data sesuai dengan halaman history()
        if ($user->isMasterAdmin()) {
            $pilars = Pilar::with(['indikators' => function($query) {
                $query->where('aktif', true)->orderBy('kode');
            }])->orderBy('urutan')->get();

            foreach ($pilars as $pilar) {
                foreach ($pilar->indikators as $indikator) {
                    $indikator->historiData = $this->getIndikatorHistoriData($indikator->id, $tahun);
                }
            }

            // Output ke PDF (contoh implementasi sederhana)
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diekspor ke PDF',
                'tahun' => $tahun
            ]);
        }

        // Jika bukan master admin, redirect ke halaman riwayat
        return redirect()->route('kpi.history')->with('error', 'Anda tidak memiliki akses untuk mengekspor data ini');
    }

    /**
     * Menampilkan detail riwayat KPI per indikator
     */
    public function detailRiwayat(Request $request, $indikatorId)
    {
        $user = Auth::user();
        $tahun = $request->tahun ?? date('Y');

        $indikator = Indikator::findOrFail($indikatorId);

        // Cek apakah user memiliki akses
        if (!$user->isMasterAdmin() && !$user->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        if ($user->isAdmin()) {
            $bidang = $user->getBidang();
            if (!$bidang || $indikator->bidang_id != $bidang->id) {
                return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat indikator ini');
            }
        }

        // Ambil data riwayat
        $nilaiKPIs = NilaiKPI::where('indikator_id', $indikatorId)
            ->where('tahun', $tahun)
            ->where('periode_tipe', 'bulanan')
            ->orderBy('bulan')
            ->get();

        // Ambil data riwayat yang sudah difinalisasi
        $nilaiRiwayat = NilaiRiwayatKPI::where('indikator_id', $indikatorId)
            ->where('tahun', $tahun)
            ->where('periode_tipe', 'bulanan')
            ->orderBy('bulan')
            ->get();

        // Catat log aktivitas
        AktivitasLog::log(
            $user,
            'view',
            'Melihat detail riwayat KPI untuk indikator ' . $indikator->nama,
            'Akses detail riwayat',
            $indikator,
            [
                'indikator_id' => $indikatorId,
                'tahun' => $tahun
            ],
            $request->ip(),
            $request->userAgent()
        );

        return view('kpi.detail_riwayat', compact('indikator', 'nilaiKPIs', 'nilaiRiwayat', 'tahun'));
    }

    /**
     * Finalisasi nilai KPI untuk riwayat
     */
    public function finalisasiNilai(Request $request, $nilaiId)
    {
        $user = Auth::user();

        // Cek apakah user memiliki akses
        if (!$user->isMasterAdmin() && !$user->isAdmin()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk melakukan finalisasi');
        }

        $nilai = NilaiKPI::findOrFail($nilaiId);

        if ($user->isAdmin()) {
            $bidang = $user->getBidang();
            if (!$bidang || $nilai->indikator->bidang_id != $bidang->id) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk memfinalisasi nilai ini');
            }
        }

        // Buat rekam riwayat
        $riwayat = NilaiRiwayatKPI::firstOrNew([
            'nilai_kpi_id' => $nilai->id,
            'indikator_id' => $nilai->indikator_id,
            'tahun' => $nilai->tahun,
            'bulan' => $nilai->bulan,
            'periode_tipe' => $nilai->periode_tipe
        ]);

        // Isi data
        $riwayat->nilai = $nilai->nilai;
        $riwayat->target = $nilai->target;
        $riwayat->persentase = $nilai->persentase;
        $riwayat->diverifikasi = $nilai->diverifikasi;
        $riwayat->finalisasi_oleh = $user->id;
        $riwayat->finalisasi_tanggal = now();
        $riwayat->keterangan = $request->keterangan;
        $riwayat->file_bukti = $nilai->file_bukti;
        $riwayat->save();

        // Catat log aktivitas
        AktivitasLog::log(
            $user,
            'finalize',
            'Memfinalisasi nilai KPI ' . $nilai->indikator->nama . ' periode ' . $nilai->bulan . '/' . $nilai->tahun,
            'Finalisasi nilai untuk riwayat',
            $nilai->indikator,
            [
                'nilai_id' => $nilai->id,
                'indikator_id' => $nilai->indikator_id,
                'tahun' => $nilai->tahun,
                'bulan' => $nilai->bulan,
                'persentase' => $nilai->persentase,
                'keterangan' => $request->keterangan
            ],
            $request->ip(),
            $request->userAgent()
        );

        return redirect()->back()->with('success', 'Nilai KPI berhasil difinalisasi untuk riwayat');
    }

    /**
     * Pastikan semua koleksi dalam statistics array sudah dikonversi menjadi array biasa
     * untuk mencegah error pada view
     */
    private function ensureStatisticsArray($statistics)
    {
        // Jika statistics bukan array, konversi menjadi array
        if (!is_array($statistics)) {
            $statistics = is_object($statistics) && method_exists($statistics, 'toArray')
                ? $statistics->toArray()
                : (array) $statistics;
        }

        // Konversi bidang_stats menjadi array jika ada
        if (isset($statistics['bidang_stats'])) {
            if (is_object($statistics['bidang_stats']) && method_exists($statistics['bidang_stats'], 'toArray')) {
                $statistics['bidang_stats'] = $statistics['bidang_stats']->toArray();
            } elseif (!is_array($statistics['bidang_stats'])) {
                $statistics['bidang_stats'] = (array) $statistics['bidang_stats'];
            }

            // Konversi setiap item dalam bidang_stats menjadi array
            foreach ($statistics['bidang_stats'] as $key => $value) {
                if (is_object($value) && method_exists($value, 'toArray')) {
                    $statistics['bidang_stats'][$key] = $value->toArray();
                } elseif (!is_array($value)) {
                    $statistics['bidang_stats'][$key] = (array) $value;
                }
            }
        }

        // Periksa dan konversi field lain yang mungkin merupakan collection
        foreach ($statistics as $key => $value) {
            if ($key !== 'bidang_stats') { // bidang_stats sudah diproses di atas
                if (is_object($value) && method_exists($value, 'toArray')) {
                    $statistics[$key] = $value->toArray();
                } elseif (is_object($value)) {
                    $statistics[$key] = (array) $value;
                }
            }
        }

        return $statistics;
    }
}
