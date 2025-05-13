@extends('layouts.app')

@section('title', 'Realisasi KPI')
@section('page_title', 'REALISASI KPI')

@section('styles')
<style>
    /* Main Container */
    .dashboard-content {
        max-width: 1800px;
        margin: 0 auto;
        padding: 0 15px;
    }

    /* Page Header - Modern UI */
    .page-header {
        background: linear-gradient(135deg, var(--pln-blue), var(--pln-light-blue));
        color: white;
        border-radius: 12px;
        padding: 20px 25px;
        margin-bottom: 25px;
        box-shadow: 0 5px 15px rgba(0, 123, 255, 0.2);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-header h2 {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
    }

    .page-header-subtitle {
        margin-top: 5px;
        font-weight: 400;
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .page-header-actions {
        display: flex;
        gap: 10px;
    }

    .page-header-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
    }

    .page-header-badge i {
        margin-right: 5px;
    }

    /* Filter Card */
    .filter-card {
        background: var(--pln-surface);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 25px;
        position: relative;
        overflow: hidden;
        border: 1px solid var(--pln-border);
        box-shadow: 0 8px 20px var(--pln-shadow);
    }

    .filter-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--pln-blue), var(--pln-light-blue));
    }

    .filter-card h5 {
        font-size: 16px;
        font-weight: 600;
        color: var(--pln-text);
        margin-bottom: 15px;
    }

    /* Table Card */
    .table-card {
        background: var(--pln-surface);
        border-radius: 16px;
        margin-bottom: 25px;
        position: relative;
        overflow: hidden;
        border: 1px solid var(--pln-border);
        box-shadow: 0 8px 20px var(--pln-shadow);
    }

    .table-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--pln-blue), var(--pln-light-blue));
    }

    .table-card .card-header {
        background: linear-gradient(135deg, var(--pln-blue), var(--pln-light-blue));
        color: white;
        border: none;
        border-radius: 16px 16px 0 0;
        padding: 15px 20px;
    }

    .table-card .card-body {
        padding: 20px;
    }

    /* Data Table */
    .data-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        color: var(--pln-text);
    }

    .data-table th,
    .data-table td {
        padding: 15px;
        border-bottom: 1px solid var(--pln-border);
    }

    .data-table th {
        background-color: var(--pln-accent-bg);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        color: var(--pln-text);
    }

    .data-table tbody tr:hover {
        background-color: var(--pln-accent-bg);
    }

    /* Progress bar */
    .progress-wrapper {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .progress {
        height: 8px;
        border-radius: 4px;
        background-color: var(--pln-accent-bg);
        overflow: hidden;
    }

    .progress-value {
        font-weight: 600;
        font-size: 0.9rem;
    }

    /* Status badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-badge i {
        margin-right: 5px;
    }

    .status-badge.verified {
        background-color: rgba(40, 167, 69, 0.15);
        color: #28a745;
    }

    .status-badge.pending {
        background-color: rgba(255, 193, 7, 0.15);
        color: #ffc107;
    }

    /* Action buttons */
    .action-buttons {
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
    }

    .action-buttons .btn {
        border-radius: 50px;
        padding: 5px 15px;
        font-size: 0.75rem;
        white-space: nowrap;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .filter-group {
            flex-direction: column;
        }
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .page-header-actions {
            width: 100%;
            justify-content: flex-start;
            margin-top: 10px;
        }
    }
</style>
@endsection

@section('content')
<div class="dashboard-content">
    <!-- Modern Page Header -->
    <div class="page-header">
        <div>
            <h2><i class="fas fa-chart-line me-2"></i>Realisasi KPI</h2>
            <div class="page-header-subtitle">
                Pengelolaan data realisasi kinerja bidang
            </div>
        </div>
        <div class="page-header-actions">
            @if(isset($indikators) && count($indikators) > 0)
            <div class="page-header-badge">
                <i class="fas fa-tasks"></i>
                Total Indikator: {{ count($indikators) }}
            </div>
            @endif
        </div>
    </div>

    @include('components.alert')

    <!-- Filter Section -->
    <div class="filter-card">
        <h5><i class="fas fa-filter me-2"></i>Filter Data Realisasi</h5>
        <form method="GET" action="{{ route('realisasi.index') }}">
            <div class="row">
                <div class="col-md-10">
                    <div class="d-flex gap-2 flex-wrap filter-group">
                        <div class="flex-grow-1">
                            <label class="form-label">Tahun</label>
                            <select name="tahun" class="form-select">
                                @for($i = date('Y'); $i >= 2020; $i--)
                                    <option value="{{ $i }}" {{ request('tahun', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="flex-grow-1">
                            <label class="form-label">Bulan</label>
                            <select name="bulan" class="form-select">
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ request('bulan', date('m')) == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="flex-grow-1">
                            <label class="form-label">Periode</label>
                            <select name="periode_tipe" class="form-select">
                                <option value="bulanan" {{ request('periode_tipe', 'bulanan') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                                <option value="mingguan" {{ request('periode_tipe') == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100 mt-2">
                        <i class="fas fa-search me-1"></i> Tampilkan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="table-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold">Data Realisasi KPI</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Bidang</th>
                            <th width="25%">Indikator</th>
                            <th width="10%">Target</th>
                            <th width="10%">Realisasi</th>
                            <th width="15%">Capaian</th>
                            <th width="10%">Status</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($indikators ?? [] as $index => $indikator)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $indikator->bidang->nama }}</td>
                                <td>
                                    <strong>{{ $indikator->kode }}</strong> - {{ $indikator->nama }}
                                    @if($indikator->deskripsi)
                                        <div class="small text-muted">{{ Str::limit($indikator->deskripsi, 60) }}</div>
                                    @endif
                                </td>
                                <td>{{ number_format($indikator->target, 2) }}</td>
                                <td>{{ isset($indikator->realisasi) ? number_format($indikator->realisasi, 2) : '-' }}</td>
                                <td>
                                    <div class="progress-wrapper">
                                        <div class="progress">
                                            @php
                                                $progressClass = 'bg-danger';
                                                if ($indikator->persentase >= 90) {
                                                    $progressClass = 'bg-success';
                                                } elseif ($indikator->persentase >= 70) {
                                                    $progressClass = 'bg-warning';
                                                }
                                            @endphp
                                            <div class="progress-bar {{ $progressClass }}" role="progressbar" style="width: {{ min($indikator->persentase, 100) }}%" aria-valuenow="{{ $indikator->persentase }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div class="progress-value">{{ number_format($indikator->persentase, 2) }}%</div>
                                    </div>
                                </td>
                                <td>
                                    @if(isset($indikator->diverifikasi))
                                        @if($indikator->diverifikasi)
                                            <div class="status-badge verified">
                                                <i class="fas fa-check-circle"></i> Diverifikasi
                                            </div>
                                        @else
                                            <div class="status-badge pending">
                                                <i class="fas fa-clock"></i> Belum Diverifikasi
                                            </div>
                                        @endif
                                    @else
                                        <div class="status-badge pending">
                                            <i class="fas fa-minus-circle"></i> Belum Diinput
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        @if($indikator->nilai_id)
                                            <a href="{{ route('realisasi.edit', $indikator->nilai_id) }}" class="btn btn-warning">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="{{ route('realisasi.show', $indikator->nilai_id) }}" class="btn btn-info">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                        @else
                                            <a href="{{ route('realisasi.create', ['indikator_id' => $indikator->id, 'tahun' => request('tahun', date('Y')), 'bulan' => request('bulan', date('m')), 'periode_tipe' => request('periode_tipe', 'bulanan')]) }}" class="btn btn-primary">
                                                <i class="fas fa-plus"></i> Input Data
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">Tidak ada data indikator untuk ditampilkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize tooltips if needed
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
    });
</script>
@endsection
