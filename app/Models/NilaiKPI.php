<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\ActivityLoggable;

class NilaiKPI extends Model
{
    use HasFactory, ActivityLoggable;

    protected $table = 'nilai_kpi';
    protected $guarded = ['id'];

    protected $fillable = [
        'indikator_id',
        'user_id',
        'tahun',
        'bulan',
        'minggu',
        'periode_tipe',
        'nilai',
        'persentase',
        'keterangan',
        'diverifikasi',
        'verifikasi_oleh',
        'verifikasi_pada',
    ];

    protected $casts = [
        'diverifikasi' => 'boolean',
        'verifikasi_pada' => 'datetime',
        'nilai' => 'float',
        'persentase' => 'float',
    ];

    /**
     * Mendapatkan indikator terkait nilai ini
     */
    public function indikator(): BelongsTo
    {
        return $this->belongsTo(Indikator::class, 'indikator_id');
    }

    /**
     * Mendapatkan user yang menginput nilai
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendapatkan user yang memverifikasi nilai
     */
    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verifikasi_oleh');
    }

    /**
     * Mendapatkan tahun penilaian terkait nilai ini
     */
    public function tahunPenilaian(): BelongsTo
    {
        return $this->belongsTo(TahunPenilaian::class, 'tahun', 'tahun');
    }

    /**
     * Mendapatkan nilai finalisasi (riwayat) terkait nilai ini
     */
    public function riwayat()
    {
        return $this->hasOne(NilaiRiwayatKPI::class, 'nilai_kpi_id');
    }

    /**
     * Scope untuk memfilter berdasarkan periode tertentu
     */
    public function scopePeriode($query, int $tahun, int $bulan = null, string $periodeTipe = null)
    {
        $query->where('tahun', $tahun);

        if ($bulan !== null) {
            $query->where('bulan', $bulan);
        }

        if ($periodeTipe !== null) {
            $query->where('periode_tipe', $periodeTipe);
        }

        return $query;
    }

    /**
     * Mendapatkan nama bulan dari angka bulan
     */
    public function getNamaBulanAttribute(): string
    {
        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        return $namaBulan[$this->bulan] ?? '';
    }

    /**
     * Mendapatkan judul untuk log aktivitas
     */
    public function getActivityLogTitle()
    {
        return 'Nilai KPI ' . $this->indikator->nama . ' ' . $this->bulan . '/' . $this->tahunPenilaian->tahun;
    }

    /**
     * Fungsi untuk cek status verifikasi
     */
    public function isVerified()
    {
        return $this->diverifikasi;
    }

    /**
     * Fungsi untuk riwayat status
     */
    public function hasRiwayat()
    {
        return $this->riwayat()->exists();
    }
}
