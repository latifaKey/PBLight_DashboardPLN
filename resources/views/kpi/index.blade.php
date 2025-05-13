@extends('layouts.app')

@section('title', 'Data KPI')
@section('page_title', 'DATA KPI')

@section('styles')
<link rel="stylesheet" href="{{ asset('pln-animations.css') }}">
<style>
    .kpi-container {
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
        width: 100%;
    }

    .card-header {
        background: linear-gradient(135deg, var(--pln-blue), var(--pln-light-blue));
        color: white;
        padding: 20px 25px;
        border-radius: 12px 12px 0 0 !important;
        border: none !important;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }

    .card-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at top right, rgba(255, 255, 255, 0.2), transparent 70%);
        z-index: 1;
    }

    .card-title {
        font-weight: 700;
        font-size: 1.4rem;
        margin: 0;
        position: relative;
        z-index: 2;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        width: 100%;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .card-body {
        background: var(--pln-surface);
        border-radius: 0 0 16px 16px;
        padding: 25px;
    }

    .input-group {
        margin-bottom: 20px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .input-group:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        transform: translateY(-3px);
    }

    .form-control {
        border: 1px solid var(--pln-border);
        padding: 12px 15px;
        background: var(--pln-accent-bg);
        color: var(--pln-text);
        transition: all 0.3s ease;
        border-radius: 0;
    }

    .form-control:focus {
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.15);
        border-color: var(--pln-light-blue);
        outline: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--pln-blue), var(--pln-light-blue));
        border: none;
        border-radius: 0 12px 12px 0;
        padding: 12px 20px;
        font-weight: 600;
        color: white;
        box-shadow: 0 5px 15px rgba(0, 123, 255, 0.2);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: 0.5s;
    }

    .btn-primary:hover::before {
        left: 100%;
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 123, 255, 0.3);
    }

    .btn-action {
        border-radius: 30px;
        padding: 8px 15px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .btn-action i {
        margin-right: 6px;
        transition: all 0.3s ease;
    }

    .btn-action:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }

    .btn-action:hover i {
        transform: translateY(-2px);
    }

    .btn-action::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: 0.5s;
    }

    .btn-action:hover::before {
        left: 100%;
    }

    .progress {
        height: 8px;
        background: rgba(0, 0, 0, 0.05);
        border-radius: 4px;
        overflow: hidden;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-top: 8px;
    }

    .progress-bar {
        background: linear-gradient(90deg, var(--pln-blue), var(--pln-light-blue));
        border-radius: 4px;
        position: relative;
        overflow: hidden;
        transition: width 0.8s ease;
    }

    .progress-bar::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.4) 50%, transparent 100%);
        animation: shine 2s infinite linear;
        background-size: 200% 100%;
    }

    .table-responsive {
        border-radius: 16px;
        overflow-x: auto;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        margin-top: 25px;
        width: 100%;
        -webkit-overflow-scrolling: touch;
    }

    .modern-table {
        width: 100%;
        min-width: 900px;
        border-collapse: separate;
        border-spacing: 0 8px;
        table-layout: fixed;
        margin: 0;
    }

    .modern-table th, .modern-table td {
        padding: 15px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .filter-container {
        background: rgba(255, 255, 255, 0.03);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 25px;
        border: 1px solid var(--pln-border);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        width: 100%;
    }

    .filter-container:hover {
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
    }

    .filter-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 15px;
        color: var(--pln-blue);
        display: flex;
        align-items: center;
    }

    .filter-title i {
        margin-right: 8px;
        background: rgba(0, 123, 255, 0.1);
        padding: 7px;
        border-radius: 50%;
        color: var(--pln-blue);
        transition: all 0.3s ease;
    }

    .filter-container:hover .filter-title i {
        transform: rotate(15deg);
        background: rgba(0, 123, 255, 0.2);
    }

    .status-container {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        margin-top: 25px;
        width: 100%;
    }

    .status-card {
        flex: 1;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 16px;
        padding: 20px;
        text-align: center;
        border: 1px solid var(--pln-border);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        min-width: 180px;
    }

    .status-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
    }

    .status-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .status-icon::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background: inherit;
        border-radius: 50%;
        z-index: -1;
        opacity: 0.4;
        transform: scale(1.3);
        filter: blur(10px);
    }

    .status-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .status-count {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .status-text {
        color: var(--pln-text-secondary);
        font-size: 0.9rem;
    }

    .success-icon {
        background: linear-gradient(135deg, #28a745, #5bba6f);
        color: white;
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
    }

    .warning-icon {
        background: linear-gradient(135deg, #ffc107, #ffdb4d);
        color: #212529;
        box-shadow: 0 5px 15px rgba(255, 193, 7, 0.3);
    }

    .danger-icon {
        background: linear-gradient(135deg, #dc3545, #ef5350);
        color: white;
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
    }

    .info-icon {
        background: linear-gradient(135deg, var(--pln-blue), var(--pln-light-blue));
        color: white;
        box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
    }

    @media (max-width: 768px) {
        .status-card {
            min-width: 100%;
            margin-bottom: 15px;
        }

        .status-container {
            flex-direction: column;
            gap: 10px;
        }

        .modern-table th, .modern-table td {
            padding: 10px;
            font-size: 0.85rem;
        }

        .card-body {
            padding: 15px;
        }

        .filter-container {
            padding: 15px;
        }
    }

    @media (max-width: 576px) {
        .modern-table {
            min-width: 800px;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row fade-in-up">
        <div class="col-md-12">
            <div class="card glass-morphism shadow-soft w-100">
                <div class="card-header gradient-blue">
                    <h5 class="card-title fade-in-left">
                        <i class="fas fa-chart-line mr-2"></i> Data KPI
                    </h5>
                    <div class="fade-in-right">
                        <a href="{{ route('kpi.create') }}" class="btn btn-modern btn-light btn-icon">
                            <i class="fas fa-plus"></i> Tambah KPI Baru
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="filter-container fade-in-up delay-100 w-100">
                        <div class="filter-title">
                            <i class="fas fa-filter"></i> Filter Data
                        </div>
                        <form action="{{ route('kpi.index') }}" method="GET" class="w-100">
                            <div class="row w-100">
                                <div class="col-md-3 mb-3">
                                    <label for="tahun" class="form-label">Tahun</label>
                                    <select class="form-select form-control" name="tahun" id="tahun">
                                        <option value="">Semua Tahun</option>
                                        @for($year = date('Y'); $year >= 2020; $year--)
                                            <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="periode" class="form-label">Periode</label>
                                    <select class="form-select form-control" name="periode" id="periode">
                                        <option value="">Semua Periode</option>
                                        @foreach(['Triwulan 1', 'Triwulan 2', 'Triwulan 3', 'Triwulan 4', 'Semester 1', 'Semester 2', 'Tahunan'] as $p)
                                            <option value="{{ $p }}" {{ request('periode') == $p ? 'selected' : '' }}>{{ $p }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select form-control" name="status" id="status">
                                        <option value="">Semua Status</option>
                                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                                        <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                                        <option value="finalized" {{ request('status') == 'finalized' ? 'selected' : '' }}>Finalized</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-search mr-2"></i> Cari
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="status-container">
                        <div class="status-card fade-in-up delay-200">
                            <div class="status-icon info-icon pulse">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="status-title">Total KPI</div>
                            <div class="status-count text-gradient">{{ $totalKpi ?? 0 }}</div>
                            <div class="status-text">Semua data KPI</div>
                        </div>

                        <div class="status-card fade-in-up delay-300">
                            <div class="status-icon success-icon pulse">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="status-title">Terverifikasi</div>
                            <div class="status-count text-gradient">{{ $verifiedCount ?? 0 }}</div>
                            <div class="status-text">KPI yang sudah diverifikasi</div>
                        </div>

                        <div class="status-card fade-in-up delay-400">
                            <div class="status-icon warning-icon pulse">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="status-title">Menunggu</div>
                            <div class="status-count text-gradient">{{ $pendingCount ?? 0 }}</div>
                            <div class="status-text">KPI yang menunggu verifikasi</div>
                        </div>

                        <div class="status-card fade-in-up delay-500">
                            <div class="status-icon danger-icon pulse">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="status-title">Draft</div>
                            <div class="status-count text-gradient">{{ $draftCount ?? 0 }}</div>
                            <div class="status-text">KPI dalam status draft</div>
                        </div>
                    </div>

                    <div class="table-responsive mt-4 fade-in-up delay-300 w-100">
                        <table class="modern-table w-100">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 15%;">Periode</th>
                                    <th style="width: 20%;">Pegawai</th>
                                    <th style="width: 15%;">Tanggal Penilaian</th>
                                    <th style="width: 15%;">Nilai</th>
                                    <th style="width: 15%;">Status</th>
                                    <th style="width: 15%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($kpiList) && count($kpiList) > 0)
                                    @foreach($kpiList as $index => $kpi)
                                    <tr class="fade-in-up" style="animation-delay: {{ 300 + ($index * 50) }}ms">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $kpi->periode_kpi->tahun }} - {{ $kpi->periode_kpi->periode }}</td>
                                        <td>
                                            <div>{{ $kpi->pegawai->nama }}</div>
                                            <small class="text-muted">{{ $kpi->pegawai->nip }}</small>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($kpi->tanggal_penilaian)->translatedFormat('d F Y') }}</td>
                                        <td>
                                            @php
                                                $nilaiClass = 'status-badge-danger';
                                                if ($kpi->nilai_akhir >= 90) {
                                                    $nilaiClass = 'status-badge-success';
                                                } elseif ($kpi->nilai_akhir >= 70) {
                                                    $nilaiClass = 'status-badge-warning';
                                                }
                                            @endphp
                                            <span class="status-badge {{ $nilaiClass }}">{{ $kpi->nilai_akhir }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $statusClass = 'status-badge-info';
                                                $icon = 'check-circle';

                                                if ($kpi->status == 'Draft') {
                                                    $statusClass = 'status-badge-danger';
                                                    $icon = 'edit';
                                                } elseif ($kpi->status == 'Submitted') {
                                                    $statusClass = 'status-badge-warning';
                                                    $icon = 'clock';
                                                } elseif ($kpi->status == 'Verified') {
                                                    $statusClass = 'status-badge-success';
                                                    $icon = 'check-circle';
                                                }
                                            @endphp
                                            <span class="status-badge {{ $statusClass }}">
                                                <i class="fas fa-{{ $icon }}"></i> {{ $kpi->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('kpi.show', $kpi->id) }}" class="btn btn-modern btn-sm btn-primary btn-icon me-2 shadow-hover">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                                <a href="{{ route('kpi.edit', $kpi->id) }}" class="btn btn-modern btn-sm btn-warning btn-icon shadow-hover">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="7">
                                        <div class="empty-state fade-in">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-search"></i>
                                            </div>
                                            <h4 class="mt-3">Tidak Ada Data</h4>
                                            <p class="empty-state-text">
                                                Tidak ada data KPI yang ditemukan dengan filter yang dipilih
                                            </p>
                                            <a href="{{ route('kpi.index') }}" class="empty-state-btn">
                                                <i class="fas fa-sync-alt mr-2"></i> Reset Filter
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    @if(isset($kpiList) && method_exists($kpiList, 'links'))
                    <div class="d-flex justify-content-center mt-4 fade-in-up delay-400">
                        {{ $kpiList->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Inisialisasi animasi saat halaman dimuat
        setTimeout(function() {
            $('.fade-in').css({
                'animation': 'fadeIn 0.8s ease-out forwards'
            });

            $('.fade-in-up').css({
                'animation': 'fadeInUp 0.8s ease-out forwards'
            });

            $('.fade-in-down').css({
                'animation': 'fadeInDown 0.8s ease-out forwards'
            });

            $('.fade-in-left').css({
                'animation': 'fadeInLeft 0.8s ease-out forwards'
            });

            $('.fade-in-right').css({
                'animation': 'fadeInRight 0.8s ease-out forwards'
            });
        }, 100);

        // Efek hover pada card
        $('.status-card').hover(
            function() {
                $(this).addClass('card-hover');
            },
            function() {
                $(this).removeClass('card-hover');
            }
        );

        // Efek ripple pada tombol
        $('.btn').on('click', function(e) {
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

        // Animasi progress bar
        $('.progress-bar').each(function() {
            let width = $(this).attr('aria-valuenow') + '%';
            $(this).css('width', '0%').animate({
                width: width
            }, 1000);
        });
    });
</script>
@endsection
