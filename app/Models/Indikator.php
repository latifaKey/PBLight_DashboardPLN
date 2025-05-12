<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\ActivityLoggable;

class Indikator extends Model
{
    use HasFactory, ActivityLoggable;

    protected $fillable = [
        'pilar_id',
        'bidang_id',
        'kode',
        'nama',
        'deskripsi',
        'bobot',
        'target',
        'urutan',
        'aktif',
    ];

    /**
     * Mendapatkan pilar yang terkait dengan indikator ini
     */
    public function pilar(): BelongsTo
    {
        return $this->belongsTo(Pilar::class);
    }

    /**
     * Mendapatkan bidang PIC yang bertanggung jawab
     */
    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class);
    }

    /**
     * Mendapatkan semua nilai KPI untuk indikator ini
     */
    public function nilaiKPIs(): HasMany
    {
        return $this->hasMany(NilaiKPI::class);
    }

    /**
     * Mendapatkan semua target KPI untuk indikator ini
     */
    public function targetKPIs(): HasMany
    {
        return $this->hasMany(TargetKPI::class);
    }

    /**
     * Mendapatkan nilai KPI untuk periode tertentu
     *
     * @param int $tahun
     * @param int $bulan
     * @param string $periodeTipe
     * @return float
     */
    public function getNilai(int $tahun, int $bulan, string $periodeTipe = 'bulanan'): float
    {
        $nilaiKPI = $this->nilaiKPIs()
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->where('periode_tipe', $periodeTipe)
            ->first();

        return $nilaiKPI ? $nilaiKPI->persentase : 0;
    }

    /**
     * Mendapatkan target KPI untuk tahun penilaian tertentu
     *
     * @param int $tahunPenilaianId
     * @return TargetKPI|null
     */
    public function getTarget(int $tahunPenilaianId)
    {
        return $this->targetKPIs()
            ->where('tahun_penilaian_id', $tahunPenilaianId)
            ->first();
    }

    /**
     * Mendapatkan data historis nilai indikator
     *
     * @param int $tahun
     * @param string $periodeTipe
     * @return array
     */
    public function getHistoryData(int $tahun, string $periodeTipe = 'bulanan'): array
    {
        return $this->nilaiKPIs()
            ->where('tahun', $tahun)
            ->where('periode_tipe', $periodeTipe)
            ->orderBy('bulan')
            ->orderBy('minggu')
            ->get()
            ->toArray();
    }

    /**
     * Mendapatkan judul untuk log aktivitas
     */
    public function getActivityLogTitle()
    {
        return $this->nama;
    }
}
