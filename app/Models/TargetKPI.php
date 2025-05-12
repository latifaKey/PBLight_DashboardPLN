<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\ActivityLoggable;

class TargetKPI extends Model
{
    use HasFactory, ActivityLoggable;

    protected $table = 'target_kpi';

    protected $fillable = [
        'indikator_id',
        'tahun_penilaian_id',
        'user_id',
        'target_tahunan',
        'target_bulanan',
        'keterangan',
        'disetujui',
        'disetujui_oleh',
        'disetujui_pada',
    ];

    protected $casts = [
        'target_tahunan' => 'float',
        'target_bulanan' => 'array',
        'disetujui' => 'boolean',
        'disetujui_pada' => 'datetime',
    ];

    /**
     * Mendapatkan indikator terkait target ini
     */
    public function indikator(): BelongsTo
    {
        return $this->belongsTo(Indikator::class);
    }

    /**
     * Mendapatkan tahun penilaian terkait target ini
     */
    public function tahunPenilaian(): BelongsTo
    {
        return $this->belongsTo(TahunPenilaian::class);
    }

    /**
     * Mendapatkan user yang menginput target
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendapatkan user yang menyetujui target
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    /**
     * Mendapatkan target bulan tertentu
     *
     * @param int $bulan (1-12)
     * @return float
     */
    public function getTargetBulan(int $bulan): float
    {
        if ($this->target_bulanan && isset($this->target_bulanan[$bulan-1])) {
            return (float) $this->target_bulanan[$bulan-1];
        }

        // Jika target bulanan tidak ada, gunakan target tahunan / 12
        return $this->target_tahunan / 12;
    }

    /**
     * Mendapatkan persentase pencapaian target berdasarkan nilai tertentu
     *
     * @param float $nilai
     * @param int $bulan
     * @return float
     */
    public function hitungPersentasePencapaian(float $nilai, int $bulan = null): float
    {
        $target = $bulan ? $this->getTargetBulan($bulan) : $this->target_tahunan;

        if ($target <= 0) {
            return 0;
        }

        return min(100, ($nilai / $target) * 100);
    }

    /**
     * Mendapatkan judul untuk log aktivitas
     */
    public function getActivityLogTitle()
    {
        return 'Target KPI ' . $this->indikator->nama . ' Tahun ' . $this->tahun_penilaian->tahun;
    }
}
