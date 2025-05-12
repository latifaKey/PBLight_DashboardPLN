<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NilaiRiwayatKPI extends Model
{
    use HasFactory;

    protected $table = 'nilai_riwayat_kpi';
    protected $guarded = ['id'];

    /**
     * Atribut yang dapat diisi secara massal
     */
    protected $fillable = [
        'nilai_kpi_id',
        'indikator_id',
        'tahun',
        'bulan',
        'periode_tipe',
        'nilai',
        'target',
        'persentase',
        'diverifikasi',
        'finalisasi_oleh',
        'finalisasi_tanggal',
        'keterangan',
        'file_bukti',
    ];

    /**
     * Atribut yang dikonversi dari tanggal
     */
    protected $dates = [
        'finalisasi_tanggal',
        'created_at',
        'updated_at'
    ];

    /**
     * Relasi dengan nilai KPI asli
     */
    public function nilaiKPI(): BelongsTo
    {
        return $this->belongsTo(NilaiKPI::class, 'nilai_kpi_id');
    }

    /**
     * Relasi dengan indikator
     */
    public function indikator(): BelongsTo
    {
        return $this->belongsTo(Indikator::class, 'indikator_id');
    }

    /**
     * Relasi dengan user yang melakukan finalisasi
     */
    public function finalisasiUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'finalisasi_oleh');
    }

    /**
     * Mendapatkan tahun penilaian
     */
    public function tahunPenilaian(): BelongsTo
    {
        return $this->belongsTo(TahunPenilaian::class, 'tahun', 'tahun');
    }

    /**
     * Mendapatkan judul untuk log aktivitas
     */
    public function getActivityLogTitle()
    {
        return 'Riwayat KPI ' . $this->indikator->nama . ' ' . $this->bulan . '/' . $this->tahun;
    }
}
