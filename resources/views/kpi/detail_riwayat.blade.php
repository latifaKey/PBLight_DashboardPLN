@extends('layouts.app')

@section('title', 'Detail Riwayat KPI')
@section('page_title', 'DETAIL RIWAYAT KPI')

@section('styles')
<link rel="stylesheet" href="{{ asset('pln-animations.css') }}">
<style>
    .riwayat-detail-container {
        background: var(--pln-accent-bg);
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1),
                    0 5px 15px rgba(0, 123, 255, 0.1),
                    inset 0 -2px 2px rgba(255, 255, 255, 0.08);
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
        border: 1px solid var(--pln-border);
        transition: all 0.4s ease;
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
    }

    .riwayat-detail-container:hover {
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15),
                    0 8px 25px rgba(0, 123, 255, 0.15),
                    inset 0 -2px 2px rgba(255, 255, 255, 0.1);
        transform: translateY(-5px);
    }

    .indikator-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 25px;
        background: linear-gradient(135deg, var(--pln-surface), rgba(255, 255, 255, 0.05));
        padding: 20px 25px;
        border-radius: 12px;
        border-left: 5px solid var(--pln-blue);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .indikator-header:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .indikator-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at top right, rgba(255, 255, 255, 0.1), transparent 70%);
        z-index: 1;
    }

    .indikator-title {
        margin: 0;
        font-size: 1.5rem;
        color: var(--pln-blue);
        font-weight: 700;
        position: relative;
        z-index: 2;
        transition: all 0.3s ease;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .indikator-header:hover .indikator-title {
        transform: translateX(5px);
    }

    .indikator-subtitle {
        font-size: 1rem;
        color: var(--pln-text-secondary);
        margin-top: 8px;
        position: relative;
        z-index: 2;
        transition: all 0.3s ease;
    }

    .indikator-subtitle i {
        background: rgba(0, 123, 255, 0.1);
        padding: 5px;
        border-radius: 50%;
        margin-right: 5px;
        transition: all 0.3s ease;
    }

    .indikator-header:hover .indikator-subtitle i {
        transform: rotate(15deg);
        background: rgba(0, 123, 255, 0.2);
    }

    .indikator-code {
        background: linear-gradient(135deg, var(--pln-blue), var(--pln-light-blue));
        color: white;
        padding: 5px 12px;
        border-radius: 8px;
        font-weight: 600;
        margin-right: 12px;
        box-shadow: 0 3px 10px rgba(0, 123, 255, 0.2);
        transition: all 0.3s ease;
        display: inline-block;
    }

    .indikator-header:hover .indikator-code {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
    }

    .riwayat-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 8px;
        margin-bottom: 30px;
    }

    .riwayat-table th {
        background: linear-gradient(90deg, var(--pln-blue), var(--pln-light-blue));
        color: white;
        padding: 15px;
        text-align: center;
        font-weight: 600;
        position: sticky;
        top: 0;
        z-index: 10;
        text-transform: uppercase;
        font-size: 13px;
        white-space: nowrap;
    }

    .riwayat-table th:first-child {
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
    }

    .riwayat-table th:last-child {
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
    }

    .riwayat-table td {
        padding: 15px;
        border: none;
        background: rgba(255, 255, 255, 0.03);
        transition: all 0.3s ease;
        vertical-align: middle;
        text-align: center;
    }

    .riwayat-table tbody tr {
        transition: all 0.3s ease;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .riwayat-table tbody tr:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .riwayat-table tbody tr:hover td {
        background: rgba(255, 255, 255, 0.05);
    }

    .riwayat-table tbody tr td:first-child {
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
    }

    .riwayat-table tbody tr td:last-child {
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
    }

    .nilai-badge {
        padding: 6px 10px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-block;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .nilai-badge:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .nilai-bagus {
        background: linear-gradient(135deg, #28a745, #5bba6f);
        color: white;
    }

    .nilai-cukup {
        background: linear-gradient(135deg, #ffc107, #ffdb4d);
        color: #333;
    }

    .nilai-kurang {
        background: linear-gradient(135deg, #dc3545, #ef5350);
        color: white;
    }

    .btn-kembali {
        background: linear-gradient(135deg, var(--pln-blue), var(--pln-light-blue));
        border: none;
        border-radius: 30px;
        padding: 10px 22px;
        font-weight: 600;
        color: white;
        box-shadow: 0 5px 15px rgba(0, 123, 255, 0.2);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        display: inline-flex;
        align-items: center;
    }

    .btn-kembali i {
        margin-right: 8px;
        transition: all 0.3s ease;
    }

    .btn-kembali:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 123, 255, 0.3);
        color: white;
    }

    .btn-kembali:hover i {
        transform: translateX(-3px);
    }

    .btn-kembali::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: 0.5s;
    }

    .btn-kembali:hover::before {
        left: 100%;
    }

    .periode-title {
        display: inline-flex;
        align-items: center;
        padding: 8px 15px;
        background: rgba(0, 123, 255, 0.1);
        border-radius: 8px;
        margin-bottom: 20px;
        border-left: 3px solid var(--pln-blue);
    }

    .periode-title i {
        margin-right: 10px;
        color: var(--pln-blue);
    }

    .info-section {
        background: rgba(255, 255, 255, 0.03);
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 30px;
        border: 1px solid var(--pln-border);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .info-section:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row fade-in-up">
        <div class="col-md-12">
            <div class="card shadow-soft glass-morphism">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="text-gradient mb-0">
                            <i class="fas fa-history mr-2"></i> Detail Riwayat KPI
                        </h5>
                        <a href="{{ route('kpi.history') }}" class="btn-kembali">
                            <i class="fas fa-arrow-left"></i> Kembali ke Riwayat
                        </a>
                    </div>

                    <div class="periode-title fade-in delay-100">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Periode: {{ $periodeKpi->tahun }} - {{ $periodeKpi->periode }}</span>
                    </div>

                    <div class="info-section fade-in-up delay-200">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="30%"><strong>NIP</strong></td>
                                        <td>: {{ $kpi->pegawai->nip }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nama</strong></td>
                                        <td>: {{ $kpi->pegawai->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Jabatan</strong></td>
                                        <td>: {{ $kpi->pegawai->jabatan }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="40%"><strong>Unit</strong></td>
                                        <td>: {{ $kpi->pegawai->unit->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal Penilaian</strong></td>
                                        <td>: {{ \Carbon\Carbon::parse($kpi->tanggal_penilaian)->translatedFormat('d F Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status</strong></td>
                                        <td>:
                                            <span class="status-badge status-badge-info">
                                                <i class="fas fa-check-circle"></i> Final
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    @foreach($indikator as $i)
                    <div class="riwayat-detail-container fade-in-up delay-300">
                        <div class="indikator-header">
                            <div>
                                <h4 class="indikator-title">
                                    <span class="indikator-code">{{ $i->kode }}</span>
                                    {{ $i->nama }}
                                </h4>
                                <p class="indikator-subtitle">
                                    <i class="fas fa-tag"></i> {{ $i->perspektif }}
                                    <span class="status-badge status-badge-info ml-2" style="font-size: 0.7rem;">
                                        Bobot: {{ $i->bobot }}%
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="riwayat-table">
                                <thead>
                                    <tr>
                                        <th>Target</th>
                                        <th>Realisasi</th>
                                        <th>Formula</th>
                                        <th>Polaritas</th>
                                        <th>Nilai</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($i->details as $detail)
                                    <tr class="fade-in delay-400">
                                        <td>{{ $detail->target }}</td>
                                        <td>{{ $detail->realisasi }}</td>
                                        <td>{{ $detail->formula }}</td>
                                        <td>{{ $detail->polaritas }}</td>
                                        <td>
                                            @php
                                                $nilaiClass = 'nilai-kurang';
                                                if ($detail->nilai >= 90) {
                                                    $nilaiClass = 'nilai-bagus';
                                                } elseif ($detail->nilai >= 70) {
                                                    $nilaiClass = 'nilai-cukup';
                                                }
                                            @endphp
                                            <span class="nilai-badge {{ $nilaiClass }}">
                                                {{ $detail->nilai }}
                                            </span>
                                        </td>
                                        <td>{{ $detail->keterangan }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach

                    <div class="row fade-in-up delay-500">
                        <div class="col-md-6 offset-md-3">
                            <div class="info-section text-center">
                                <h5 class="gradient-text mb-3">Nilai Akhir KPI</h5>
                                <div class="d-flex justify-content-center align-items-center mb-3">
                                    @php
                                        $nilaiAkhirClass = 'nilai-kurang';
                                        if ($kpi->nilai_akhir >= 90) {
                                            $nilaiAkhirClass = 'nilai-bagus';
                                        } elseif ($kpi->nilai_akhir >= 70) {
                                            $nilaiAkhirClass = 'nilai-cukup';
                                        }
                                    @endphp
                                    <div class="nilai-badge {{ $nilaiAkhirClass }} pulse" style="font-size: 1.5rem; padding: 10px 25px;">
                                        {{ $kpi->nilai_akhir }}
                                    </div>
                                </div>
                                <div class="text-muted">
                                    <strong>Penilaian Terverifikasi</strong><br>
                                    <small>{{ \Carbon\Carbon::parse($kpi->tanggal_verifikasi)->translatedFormat('d F Y H:i') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Menambahkan efek animasi saat halaman dimuat
        $('.fade-in').css('opacity', 0).animate({
            opacity: 1
        }, 500);

        // Animasi untuk container detail riwayat
        $('.riwayat-detail-container').each(function(index) {
            $(this).css({
                'opacity': 0,
                'transform': 'translateY(20px)'
            }).delay(300 + (index * 100)).animate({
                'opacity': 1,
                'transform': 'translateY(0)'
            }, 500);
        });

        // Efek hover pada baris tabel
        $('.riwayat-table tbody tr').hover(
            function() {
                $(this).addClass('shadow-hover');
            },
            function() {
                $(this).removeClass('shadow-hover');
            }
        );

        // Efek ripple pada tombol
        $('.btn-kembali').on('click', function(e) {
            let posX = e.pageX - $(this).offset().left;
            let posY = e.pageY - $(this).offset().top;

            let ripple = $('<span class="ripple"></span>');
            ripple.css({
                left: posX + 'px',
                top: posY + 'px'
            });

            $(this).append(ripple);

            setTimeout(function() {
                ripple.remove();
            }, 600);
        });
    });
</script>
@endsection
