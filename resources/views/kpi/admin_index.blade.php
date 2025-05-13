@extends('layouts.app')

@section('title', 'Laporan KPI Bidang')
@section('page_title', 'LAPORAN KPI BIDANG - STATUS TERKINI')

@section('styles')
<style>
    .status-periode {
        background: linear-gradient(90deg, var(--pln-blue), var(--pln-light-blue));
        color: white;
        border-radius: 8px;
        padding: 12px 20px;
        margin-bottom: 20px;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .status-periode i {
        margin-right: 8px;
    }

    .kpi-dashboard-card {
        border-radius: 12px;
        box-shadow: 0 4px 12px var(--pln-shadow);
        margin-bottom: 25px;
        border: 1px solid var(--pln-border);
        overflow: hidden;
    }

    .kpi-dashboard-card .card-header {
        background: var(--pln-accent-bg);
        border-bottom: 1px solid var(--pln-border);
        padding: 15px 20px;
    }

    .info-badge {
        background: var(--pln-blue);
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        display: inline-block;
    }

    .kpi-table {
        margin-bottom: 0;
    }

    .kpi-table th {
        background: var(--pln-accent-bg);
        border-bottom: 2px solid var(--pln-border);
    }

    .status-icon {
        font-size: 2.5rem;
        margin-bottom: 10px;
    }

    .action-btn {
        transition: all 0.2s;
    }

    .action-btn:hover {
        transform: translateY(-2px);
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="status-periode">
        <div>
            <i class="fas fa-calendar-day"></i>
            Data KPI Periode: <strong>{{ $periodeTipe == 'bulanan' ? date('F', mktime(0, 0, 0, $bulan, 1)) : 'Minggu ke-' . ($request->minggu ?? '1') }}</strong> {{ $tahun }}
        </div>
        <div class="info-badge">
            <i class="fas fa-info-circle"></i> Status saat ini
        </div>
    </div>

    <!-- Filter Section -->
    <div class="kpi-dashboard-card">
        <div class="card-header">
            <h5 class="card-title mb-0"><i class="fas fa-filter me-2"></i>Filter Periode</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('kpi.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="tahun" class="form-label">Tahun</label>
                    <select class="form-select" id="tahun" name="tahun">
                        @for ($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="bulan" class="form-label">Bulan</label>
                    <select class="form-select" id="bulan" name="bulan">
                        <option value="1" {{ $bulan == 1 ? 'selected' : '' }}>Januari</option>
                        <option value="2" {{ $bulan == 2 ? 'selected' : '' }}>Februari</option>
                        <option value="3" {{ $bulan == 3 ? 'selected' : '' }}>Maret</option>
                        <option value="4" {{ $bulan == 4 ? 'selected' : '' }}>April</option>
                        <option value="5" {{ $bulan == 5 ? 'selected' : '' }}>Mei</option>
                        <option value="6" {{ $bulan == 6 ? 'selected' : '' }}>Juni</option>
                        <option value="7" {{ $bulan == 7 ? 'selected' : '' }}>Juli</option>
                        <option value="8" {{ $bulan == 8 ? 'selected' : '' }}>Agustus</option>
                        <option value="9" {{ $bulan == 9 ? 'selected' : '' }}>September</option>
                        <option value="10" {{ $bulan == 10 ? 'selected' : '' }}>Oktober</option>
                        <option value="11" {{ $bulan == 11 ? 'selected' : '' }}>November</option>
                        <option value="12" {{ $bulan == 12 ? 'selected' : '' }}>Desember</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="periode_tipe" class="form-label">Periode</label>
                    <select class="form-select" id="periode_tipe" name="periode_tipe">
                        <option value="bulanan" {{ $periodeTipe == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                        <option value="mingguan" {{ $periodeTipe == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                    </select>
                </div>
                @if($periodeTipe == 'mingguan')
                <div class="col-md-4" id="mingguContainer">
                    <label for="minggu" class="form-label">Minggu Ke-</label>
                    <select class="form-select" id="minggu" name="minggu">
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ (request('minggu', 1) == $i) ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                @endif
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-sync-alt me-2"></i>Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Ringkasan Section -->
    <div class="kpi-dashboard-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><i class="fas fa-chart-pie me-2"></i>Ringkasan KPI {{ $bidang->nama }}</h5>
            <a href="{{ route('kpi.history') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-history me-1"></i> Lihat Riwayat
            </a>
        </div>
        <div class="card-body">
            @php
                $totalPersentase = 0;
                $count = 0;
                foreach ($indikators as $indikator) {
                    $totalPersentase += $indikator->nilai_persentase;
                    $count++;
                }
                $rataRata = $count > 0 ? round($totalPersentase / $count, 2) : 0;

                // Menentukan warna berdasarkan rata-rata
                if ($rataRata >= 80) {
                    $warna = 'success';
                    $icon = 'check-circle';
                    $status = 'BAIK';
                } elseif ($rataRata >= 60) {
                    $warna = 'warning';
                    $icon = 'exclamation-triangle';
                    $status = 'PERLU PERHATIAN';
                } else {
                    $warna = 'danger';
                    $icon = 'times-circle';
                    $status = 'KRITIS';
                }
            @endphp

            <div class="row text-center">
                <div class="col-md-6 mx-auto">
                    <div class="card bg-{{ $warna }} text-white">
                        <div class="card-body">
                            <i class="fas fa-{{ $icon }} status-icon"></i>
                            <h2 class="mb-2">{{ $rataRata }}%</h2>
                            <h5 class="mb-0">Status: {{ $status }}</h5>
                            <p class="mt-2 mb-0">Rata-rata Pencapaian KPI</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Catatan:</strong> Data ini menampilkan status KPI saat ini. Untuk melihat tren dan perbandingan antar periode, silakan kunjungi halaman <a href="{{ route('kpi.history') }}" class="alert-link">Riwayat KPI</a>.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail KPI Section -->
    <div class="kpi-dashboard-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><i class="fas fa-list-alt me-2"></i>Daftar KPI {{ $bidang->nama }}</h5>
            <div>
                <a href="{{ route('realisasi.index') }}" class="btn btn-sm btn-primary action-btn">
                    <i class="fas fa-edit me-1"></i> Input Realisasi
                </a>
                <a href="{{ route('kpi.laporan') }}?tahun={{ $tahun }}&bulan={{ $bulan }}" class="btn btn-sm btn-success action-btn ms-2">
                    <i class="fas fa-file-export me-1"></i> Ekspor Laporan
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover kpi-table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Indikator</th>
                            <th>Target</th>
                            <th>Nilai</th>
                            <th>Persentase</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($indikators as $indikator)
                            <tr>
                                <td>{{ $indikator->kode }}</td>
                                <td>{{ $indikator->nama }}</td>
                                <td>{{ $indikator->target }}</td>
                                <td>{{ $indikator->nilai_absolut }}</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-{{ $indikator->nilai_persentase >= 80 ? 'success' : ($indikator->nilai_persentase >= 60 ? 'warning' : 'danger') }}"
                                            role="progressbar"
                                            style="width: {{ $indikator->nilai_persentase }}%;"
                                            aria-valuenow="{{ $indikator->nilai_persentase }}"
                                            aria-valuemin="0"
                                            aria-valuemax="100">
                                            {{ $indikator->nilai_persentase }}%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if ($indikator->diverifikasi)
                                        <span class="badge bg-success">Diverifikasi</span>
                                    @else
                                        <span class="badge bg-warning">Belum Diverifikasi</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('kpi.show', $indikator->id) }}" class="btn btn-sm btn-info action-btn">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                        <a href="{{ route('kpi.history') }}?indikator_id={{ $indikator->id }}&tahun={{ $tahun }}" class="btn btn-sm btn-secondary action-btn">
                                            <i class="fas fa-history"></i> Riwayat
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada data KPI</td>
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
        // AJAX untuk auto-submit form saat filter berubah
        $('#tahun, #bulan, #periode_tipe, #minggu').change(function() {
            $(this).closest('form').submit();
        });

        // Tampilkan/sembunyikan minggu jika periode mingguan
        $('#periode_tipe').change(function() {
            if ($(this).val() === 'mingguan') {
                if ($('#mingguContainer').length === 0) {
                    // Tambahkan pilihan minggu jika belum ada
                    const mingguSelect = `
                    <div class="col-md-4" id="mingguContainer">
                        <label for="minggu" class="form-label">Minggu Ke-</label>
                        <select class="form-select" id="minggu" name="minggu">
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>`;
                    $(this).closest('.row').append(mingguSelect);
                    $('#minggu').change(function() {
                        $(this).closest('form').submit();
                    });
                }
            } else {
                $('#mingguContainer').remove();
            }
        });
    });
</script>
@endsection
