@extends('layouts.app')

@section('title', 'Data Kinerja')

@section('styles')
<style>
    .dashboard-content {
        max-width: 1800px;
        margin: 0 auto;
        overflow-x: hidden;
    }

    /* Dashboard Row & Column Layout */
    .dashboard-row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -12px;
    }

    .dashboard-col {
        flex: 1;
        padding: 0 12px;
        min-width: 250px;
    }

    @media (max-width: 992px) {
        .dashboard-col {
            min-width: 200px;
        }
    }

    @media (max-width: 768px) {
        .dashboard-col {
            flex: 0 0 100%;
        }
    }

    /* Section Divider */
    .section-divider {
        display: flex;
        align-items: center;
        margin: 40px 0 20px;
        position: relative;
    }

    .section-divider h2 {
        font-size: 18px;
        font-weight: 600;
        color: var(--pln-text, #333);
        margin: 0;
        padding-right: 15px;
        background: #f8f9fc;
        position: relative;
        z-index: 1;
    }

    .section-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e3e6f0;
    }

    /* Stat Cards */
    .stat-card {
        background: var(--pln-accent-bg, #ffffff);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 25px;
        position: relative;
        overflow: hidden;
        border: 1px solid var(--pln-border, #e8e8e8);
        box-shadow: 0 8px 20px var(--pln-shadow, rgba(0,0,0,0.1));
        transition: all 0.3s ease;
        height: 100%;
        width: 100%;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px var(--pln-shadow, rgba(0,0,0,0.15));
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--pln-blue, #007bff), var(--pln-light-blue, #00c6ff));
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .stat-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--pln-text, #333);
        margin: 0;
    }

    .stat-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--pln-blue, #007bff), var(--pln-light-blue, #00c6ff));
        color: white;
        font-size: 20px;
        box-shadow: 0 5px 15px rgba(0, 156, 222, 0.3);
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--pln-text, #333);
        margin: 15px 0 5px;
    }

    .stat-description {
        font-size: 13px;
        color: var(--pln-text-secondary, #6c757d);
        margin: 0;
    }

    /* Chart Cards */
    .chart-card {
        background: var(--pln-accent-bg, #ffffff);
        border-radius: 16px;
        padding: 25px;
        transition: all 0.3s ease;
        border: 1px solid var(--pln-border, #e8e8e8);
        box-shadow: 0 8px 20px var(--pln-shadow, rgba(0,0,0,0.1));
        position: relative;
        overflow: hidden;
        margin-bottom: 40px;
        height: 100%;
        width: 100%;
    }

    .chart-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--pln-blue, #007bff), var(--pln-light-blue, #00c6ff));
    }

    .chart-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px var(--pln-shadow, rgba(0,0,0,0.15));
    }

    .chart-title {
        font-size: 18px;
        color: var(--pln-light-blue, #00c6ff);
        margin-bottom: 20px;
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .chart-title i {
        margin-right: 10px;
    }

    /* Chart Container */
    .chart-container {
        position: relative;
        height: 400px;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
    }

    .chart-container.large {
        height: 450px;
    }

    .chart-container.medium {
        height: 400px;
    }

    .chart-container.small {
        height: 300px;
    }

    .medium-chart-container {
        height: 400px;
    }

    .small-chart-container {
        height: 250px;
    }

    .loading-chart {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100%;
        color: var(--pln-text-secondary, #6c757d);
        padding: 20px;
    }

    /* Progressive Bar */
    .progress {
        height: 8px;
        background-color: var(--pln-surface, #f0f0f0);
        border-radius: 4px;
        overflow: hidden;
        margin: 10px 0;
    }

    .progress-bar {
        height: 100%;
        border-radius: 4px;
        transition: width 0.5s ease-in-out;
    }

    /* Table Styling */
    .data-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px var(--pln-shadow, rgba(0,0,0,0.1));
    }

    .data-table thead th {
        background: linear-gradient(90deg, var(--pln-blue, #007bff), var(--pln-light-blue, #00c6ff));
        color: #fff;
        font-weight: 600;
        text-align: left;
        padding: 15px;
        font-size: 14px;
        border: none;
    }

    .data-table tbody tr {
        background-color: var(--pln-accent-bg, #ffffff);
        transition: all 0.2s ease;
    }

    .data-table tbody tr:hover {
        background-color: rgba(0, 156, 222, 0.05);
        transform: translateY(-2px);
    }

    .data-table td {
        padding: 12px 15px;
        border-top: 1px solid var(--pln-border, #e8e8e8);
        font-size: 14px;
        vertical-align: middle;
    }

    .data-table .badge {
        padding: 5px 10px;
        border-radius: 12px;
        font-weight: 500;
        font-size: 12px;
    }

    /* Scrollable containers */
    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
        margin-top: 40px;
    }

    .detail-section {
        background: var(--pln-accent-bg, #ffffff);
        border-radius: 16px;
        padding: 25px;
        transition: all 0.3s ease;
        border: 1px solid var(--pln-border, #e8e8e8);
        box-shadow: 0 8px 20px var(--pln-shadow, rgba(0,0,0,0.1));
        position: relative;
        overflow: hidden;
    }

    .detail-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--pln-blue, #007bff), var(--pln-light-blue, #00c6ff));
    }

    .detail-title {
        font-size: 18px;
        color: var(--pln-light-blue, #00c6ff);
        margin-bottom: 20px;
        font-weight: 600;
    }
</style>
@endsection

@section('content')
<div class="container-fluid dashboard-content">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Kinerja</h1>
        <div class="d-flex">
            <form action="{{ route('dataKinerja.index') }}" method="GET" class="d-flex align-items-center">
                <select name="tahun" class="form-control form-control-sm mr-2">
                    @foreach(range(date('Y') - 5, date('Y') + 1) as $year)
                        <option value="{{ $year }}" {{ $tahun == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
                <select name="bulan" class="form-control form-control-sm mr-2">
                    @foreach(range(1, 12) as $month)
                        <option value="{{ $month }}" {{ $bulan == $month ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $month, 1)) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fas fa-filter fa-sm"></i> Filter
                </button>
            </form>
        </div>
    </div>

    @include('components.alert')

    <!-- Ringkasan Statistik -->
    <div class="dashboard-row">
        <div class="dashboard-col">
            <div class="stat-card">
                <div class="stat-header">
                    <h3 class="stat-title">NKO Score</h3>
                    <div class="stat-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $nilaiNKO }}</div>
                <p class="stat-description">Nilai Kinerja Organisasi</p>
            </div>
        </div>

        <div class="dashboard-col">
            <div class="stat-card">
                <div class="stat-header">
                    <h3 class="stat-title">Indikator</h3>
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $totalIndikatorTercapai }}/{{ $totalIndikator }}</div>
                <p class="stat-description">Total Indikator Tercapai</p>
            </div>
        </div>

        <div class="dashboard-col">
            <div class="stat-card">
                <div class="stat-header">
                    <h3 class="stat-title">Persentase</h3>
                    <div class="stat-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $persenTercapai }}%</div>
                <div class="progress">
                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ $persenTercapai }}%"></div>
                </div>
                <p class="stat-description">Pencapaian KPI</p>
            </div>
        </div>

        <div class="dashboard-col">
            <div class="stat-card">
                <div class="stat-header">
                    <h3 class="stat-title">Target {{ $tahun }}</h3>
                    <div class="stat-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                </div>
                <div class="stat-value">100%</div>
                <p class="stat-description">Target Kinerja</p>
            </div>
        </div>
    </div>

    <!-- Chart Tren NKO -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="chart-card">
                <h3 class="chart-title"><i class="fas fa-chart-line"></i> Tren NKO {{ $tahun }}</h3>
                <div id="nkoTrendChart" class="chart-container">
                    <div class="loading-chart">
                        <i class="fas fa-circle-notch fa-spin fa-3x mb-3"></i>
                        <span>Memuat data...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section: Analisis Status Indikator -->
    <div class="section-divider">
        <h2><i class="fas fa-chart-pie mr-2"></i>Status & Komposisi Indikator</h2>
    </div>

    <!-- Grid untuk chart status -->
    <div class="row mb-5">
        <div class="col-lg-6" style="margin-bottom: 0;">
            <!-- Chart Komposisi Indikator -->
            <div class="chart-card">
                <h3 class="chart-title"><i class="fas fa-chart-pie"></i> Komposisi Indikator</h3>
                <div id="indikatorCompositionChart" class="chart-container medium-chart-container">
                    <div class="loading-chart">
                        <i class="fas fa-circle-notch fa-spin fa-3x mb-3"></i>
                        <span>Memuat data...</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6" style="margin-bottom: 0;">
            <!-- Pemetaan Status Indikator -->
            <div class="chart-card">
                <h3 class="chart-title"><i class="fas fa-map"></i> Pemetaan Status Indikator</h3>
                <div id="statusMappingChart" class="chart-container medium-chart-container">
                    <div class="loading-chart">
                        <i class="fas fa-circle-notch fa-spin fa-3x mb-3"></i>
                        <span>Memuat data...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section: Analisis Tren & Prediksi -->
    <div class="section-divider">
        <h2><i class="fas fa-chart-line mr-2"></i>Tren & Prediksi</h2>
    </div>

    <!-- Grid untuk forecast dan tren historis -->
    <div class="row mb-5">
        <div class="col-lg-6">
            <!-- Tren Historis Tahunan -->
            <div class="chart-card">
                <h3 class="chart-title"><i class="fas fa-history"></i> Tren Historis Tahunan</h3>
                <div id="historicalTrendChart" class="chart-container medium-chart-container">
                    <div class="loading-chart">
                        <i class="fas fa-circle-notch fa-spin fa-3x mb-3"></i>
                        <span>Memuat data...</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <!-- Forecast Pencapaian -->
            <div class="chart-card">
                <h3 class="chart-title"><i class="fas fa-chart-line"></i> Forecast Pencapaian</h3>
                <div id="forecastChart" class="chart-container medium-chart-container">
                    <div class="loading-chart">
                        <i class="fas fa-circle-notch fa-spin fa-3x mb-3"></i>
                        <span>Memuat data...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section: Analisis Struktur Organisasi -->
    <div class="section-divider">
        <h2><i class="fas fa-sitemap mr-2"></i>Pencapaian per Struktur</h2>
    </div>

    <!-- Grid untuk chart struktur organisasi -->
    <div class="row mb-5">
        <div class="col-lg-6">
            <!-- Pencapaian Per-Pilar -->
            <div class="chart-card">
                <h3 class="chart-title"><i class="fas fa-chart-pie"></i> Pencapaian Per-Pilar</h3>
                <div id="pilarChart" class="chart-container medium-chart-container">
                    <div class="loading-chart">
                        <i class="fas fa-circle-notch fa-spin fa-3x mb-3"></i>
                        <span>Memuat data...</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <!-- Nilai Per-Bidang -->
            <div class="chart-card">
                <h3 class="chart-title"><i class="fas fa-building"></i> Nilai Per-Bidang</h3>
                <div id="bidangChart" class="chart-container medium-chart-container">
                    <div class="loading-chart">
                        <i class="fas fa-circle-notch fa-spin fa-3x mb-3"></i>
                        <span>Memuat data...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section: Detail Indikator -->
    <div class="section-divider">
        <h2><i class="fas fa-list-alt mr-2"></i>Detail Indikator</h2>
    </div>

    <!-- Grid untuk indikator terbaik/terburuk dan perkembangan -->
    <div class="row mb-5">
        <div class="col-lg-6">
            <!-- Indikator Terbaik & Terburuk -->
            <div class="chart-card">
                <h3 class="chart-title"><i class="fas fa-trophy"></i> Indikator Terbaik & Terburuk</h3>

                @if(isset($analisisData['tertinggi']) && isset($analisisData['terendah']))
                <div class="row">
                    <div class="col-md-6 mb-4 mb-md-0">
                        <div class="stat-card" style="border-top: 4px solid #1cc88a; border-radius: 8px;">
                            <div class="stat-header">
                                <h3 class="stat-title">Indikator Terbaik</h3>
                                <div class="stat-icon" style="background: linear-gradient(135deg, #1cc88a, #0bab6c);">
                                    <i class="fas fa-trophy"></i>
                                </div>
                            </div>
                            <div class="stat-value">{{ $analisisData['tertinggi']['nilai'] }}%</div>
                            <p class="stat-description" style="font-weight: 600;">{{ $analisisData['tertinggi']['indikator']->nama }}</p>
                            <p class="stat-description">{{ $analisisData['tertinggi']['indikator']->kode }} - {{ $analisisData['tertinggi']['indikator']->bidang->nama }}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="stat-card" style="border-top: 4px solid #e74a3b; border-radius: 8px;">
                            <div class="stat-header">
                                <h3 class="stat-title">Indikator Terburuk</h3>
                                <div class="stat-icon" style="background: linear-gradient(135deg, #e74a3b, #c72c1d);">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                            </div>
                            <div class="stat-value">{{ $analisisData['terendah']['nilai'] }}%</div>
                            <p class="stat-description" style="font-weight: 600;">{{ $analisisData['terendah']['indikator']->nama }}</p>
                            <p class="stat-description">{{ $analisisData['terendah']['indikator']->kode }} - {{ $analisisData['terendah']['indikator']->bidang->nama }}</p>
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-chart-bar fa-3x text-gray-300 mb-2"></i>
                    <p>Tidak ada data indikator.</p>
                </div>
                @endif
            </div>
        </div>

        <div class="col-lg-6">
            <!-- Perkembangan Kinerja -->
            <div class="chart-card">
                <h3 class="chart-title"><i class="fas fa-chart-area"></i> Perkembangan Kinerja {{ $tahun }}</h3>

                @if(isset($analisisData['perkembangan']) && !empty($analisisData['perkembangan']))
                <div class="row">
                    <div class="col-md-6 mb-4 mb-md-0">
                        <div class="stat-card">
                            <div class="stat-header">
                                <h3 class="stat-title">Perubahan Bulan Ini</h3>
                                <div class="stat-icon" style="background:
                                    {{ $analisisData['perkembangan']['perubahan'] >= 0 ? 'linear-gradient(135deg, #1cc88a, #0bab6c)' : 'linear-gradient(135deg, #e74a3b, #c72c1d)' }};">
                                    <i class="fas fa-{{ $analisisData['perkembangan']['perubahan'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                </div>
                            </div>
                            <div class="stat-value" style="color: {{ $analisisData['perkembangan']['perubahan'] >= 0 ? '#1cc88a' : '#e74a3b' }};">
                                {{ $analisisData['perkembangan']['perubahan'] >= 0 ? '+' : '' }}{{ $analisisData['perkembangan']['perubahan'] }}%
                            </div>
                            <p class="stat-description">Dibandingkan bulan lalu</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div id="perkembanganChart" class="chart-container small-chart-container">
                            <div class="loading-chart">
                                <i class="fas fa-circle-notch fa-2x mb-2"></i>
                                <span>Memuat data...</span>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-chart-area fa-3x text-gray-300 mb-2"></i>
                    <p>Tidak ada data perkembangan.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Section: Akses Cepat -->
    <div class="section-divider">
        <h2><i class="fas fa-link mr-2"></i>Akses Cepat</h2>
    </div>

    <!-- Link Navigasi -->
    <div class="row mb-5">
        <div class="col-lg-6">
            <div class="chart-card">
                <h3 class="chart-title"><i class="fas fa-project-diagram"></i> Analisis Berdasarkan Pilar</h3>
                <p class="mb-4">Lihat data kinerja berdasarkan pilar strategis.</p>
                <a href="{{ route('dataKinerja.pilar') }}" class="btn btn-primary btn-block">
                    <i class="fas fa-project-diagram mr-1"></i> Analisis Pilar
                </a>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="chart-card">
                <h3 class="chart-title"><i class="fas fa-building"></i> Analisis Berdasarkan Bidang</h3>
                <p class="mb-4">Lihat data kinerja berdasarkan bidang.</p>
                <a href="{{ route('dataKinerja.bidang') }}" class="btn btn-info btn-block">
                    <i class="fas fa-building mr-1"></i> Analisis Bidang
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi chart ketika DOM selesai dimuat
        initCharts();
    });

    function initCharts() {
        // Data untuk chart
        const nkoTrendData = @json($trendNKO);
        const pilarData = @json($pilarData);
        const bidangData = @json($bidangData);
        const perkembanganData = @json($analisisData['perkembangan'] ?? null);

        // Data untuk chart tambahan
        const indikatorComposition = @json($indikatorComposition ?? []);
        const statusMapping = @json($statusMapping ?? []);
        const historicalTrend = @json($historicalTrend ?? []);
        const forecastData = @json($forecastData ?? []);

        // Chart Tren NKO
        if (nkoTrendData && nkoTrendData.length > 0 && typeof ApexCharts !== 'undefined') {
            const nkoTrendOptions = {
                series: [{
                    name: 'NKO',
                    data: nkoTrendData.map(item => item.nilai)
                }],
                chart: {
                    type: 'line',
                    height: 300,
                    fontFamily: 'Poppins, sans-serif',
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    }
                },
                colors: ['#4e73df'],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                xaxis: {
                    categories: nkoTrendData.map(item => item.bulan),
                    labels: {
                        rotate: -45,
                        rotateAlways: false,
                        style: {
                            fontSize: '10px'
                        }
                    }
                },
                yaxis: {
                    min: 0,
                    max: 100,
                    labels: {
                        formatter: function(val) {
                            return val.toFixed(0) + '%';
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val.toFixed(2) + '%';
                        }
                    }
                },
                grid: {
                    borderColor: '#e0e0e0',
                    strokeDashArray: 4,
                    xaxis: {
                        lines: {
                            show: true
                        }
                    }
                },
                markers: {
                    size: 5,
                    colors: ['#fff'],
                    strokeColors: '#4e73df',
                    strokeWidth: 2,
                    hover: {
                        size: 7
                    }
                }
            };

            const nkoTrendChart = new ApexCharts(document.querySelector('#nkoTrendChart'), nkoTrendOptions);
            nkoTrendChart.render();
            document.querySelector('#nkoTrendChart .loading-chart').style.display = 'none';
        }

        // Chart Pilar
        if (pilarData && pilarData.length > 0 && typeof ApexCharts !== 'undefined') {
            const pilarOptions = {
                series: pilarData.map(item => item.nilai),
                chart: {
                    type: 'donut',
                    height: 300,
                    fontFamily: 'Poppins, sans-serif',
                },
                labels: pilarData.map(item => item.nama),
                colors: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#6f42c1'],
                dataLabels: {
                    enabled: false
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val.toFixed(2) + '%';
                        }
                    }
                },
                legend: {
                    position: 'bottom',
                    fontSize: '13px',
                    markers: {
                        fillColors: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#6f42c1']
                    }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '16px',
                                    fontWeight: 600,
                                    color: '#555'
                                },
                                value: {
                                    show: true,
                                    fontSize: '22px',
                                    fontWeight: 700,
                                    color: '#333',
                                    formatter: function(val) {
                                        return val.toFixed(2) + '%';
                                    }
                                },
                                total: {
                                    show: true,
                                    label: 'Rata-rata',
                                    fontSize: '16px',
                                    fontWeight: 600,
                                    color: '#555',
                                    formatter: function(w) {
                                        const sum = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                        const avg = sum / w.globals.seriesTotals.length;
                                        return avg.toFixed(2) + '%';
                                    }
                                }
                            }
                        }
                    }
                }
            };

            const pilarChart = new ApexCharts(document.querySelector('#pilarChart'), pilarOptions);
            pilarChart.render();
            document.querySelector('#pilarChart .loading-chart').style.display = 'none';
        }

        // Chart Bidang
        if (bidangData && bidangData.length > 0 && typeof ApexCharts !== 'undefined') {
            const bidangOptions = {
                series: [{
                    name: 'Nilai',
                    data: bidangData.map(item => item.nilai)
                }],
                chart: {
                    type: 'bar',
                    height: 300,
                    fontFamily: 'Poppins, sans-serif',
                    toolbar: {
                        show: false
                    }
                },
                colors: ['#36b9cc'],
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: bidangData.map(item => item.nama),
                    labels: {
                        rotate: -45,
                        rotateAlways: false,
                        style: {
                            fontSize: '10px'
                        }
                    }
                },
                yaxis: {
                    min: 0,
                    max: 100,
                    labels: {
                        formatter: function(val) {
                            return val.toFixed(0) + '%';
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val.toFixed(2) + '%';
                        }
                    }
                },
                grid: {
                    borderColor: '#e0e0e0',
                    strokeDashArray: 4
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        columnWidth: '70%',
                        dataLabels: {
                            position: 'top'
                        }
                    }
                }
            };

            const bidangChart = new ApexCharts(document.querySelector('#bidangChart'), bidangOptions);
            bidangChart.render();
            document.querySelector('#bidangChart .loading-chart').style.display = 'none';
        }

        // Chart Perkembangan
        if (perkembanganData && perkembanganData.length > 0 && typeof ApexCharts !== 'undefined') {
            const perkembanganOptions = {
                series: [{
                    name: 'NKO',
                    data: perkembanganData.map(item => item.nilai)
                }],
                chart: {
                    type: 'area',
                    height: 150,
                    fontFamily: 'Poppins, sans-serif',
                    sparkline: {
                        enabled: true
                    },
                    toolbar: {
                        show: false
                    }
                },
                colors: ['#1cc88a'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.2,
                        stops: [0, 90, 100]
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                tooltip: {
                    fixed: {
                        enabled: false
                    },
                    x: {
                        show: true
                    },
                    y: {
                        formatter: function(val) {
                            return val.toFixed(2) + '%';
                        }
                    }
                },
                markers: {
                    size: 3,
                    colors: ['#fff'],
                    strokeColors: '#1cc88a',
                    strokeWidth: 2
                }
            };

            const perkembanganChart = new ApexCharts(document.querySelector('#perkembanganChart'), perkembanganOptions);
            perkembanganChart.render();
            document.querySelector('#perkembanganChart .loading-chart').style.display = 'none';
        }

        // Chart Komposisi Indikator
        if (indikatorComposition && indikatorComposition.length > 0 && typeof ApexCharts !== 'undefined') {
            const compositionOptions = {
                series: indikatorComposition.map(item => item.jumlah),
                chart: {
                    type: 'pie',
                    height: 300,
                    fontFamily: 'Poppins, sans-serif',
                    toolbar: {
                        show: false
                    }
                },
                labels: indikatorComposition.map(item => item.status),
                colors: ['#1cc88a', '#f6c23e', '#e74a3b'],
                legend: {
                    position: 'bottom',
                    fontSize: '13px'
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val, opts) {
                        const name = opts.w.globals.labels[opts.seriesIndex];
                        return [name, val.toFixed(1) + '%'];
                    },
                    style: {
                        fontSize: '12px'
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + ' indikator';
                        }
                    }
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            height: 250
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            document.querySelector('#indikatorCompositionChart .loading-chart').style.display = 'none';
            const compositionChart = new ApexCharts(document.querySelector("#indikatorCompositionChart"), compositionOptions);
            compositionChart.render();
        }

        // Chart Pemetaan Status Indikator
        if (statusMapping && statusMapping.length > 0 && typeof ApexCharts !== 'undefined') {
            const statusMappingOptions = {
                series: statusMapping.map(item => item.jumlah),
                chart: {
                    type: 'polarArea',
                    height: 300,
                    fontFamily: 'Poppins, sans-serif'
                },
                labels: statusMapping.map(item => item.status),
                colors: statusMapping.map(item => item.color),
                stroke: {
                    width: 1
                },
                fill: {
                    opacity: 0.8
                },
                legend: {
                    position: 'bottom',
                    fontSize: '13px'
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            height: 250
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            document.querySelector('#statusMappingChart .loading-chart').style.display = 'none';
            const statusMappingChart = new ApexCharts(document.querySelector("#statusMappingChart"), statusMappingOptions);
            statusMappingChart.render();
        }

        // Chart Tren Historis Tahunan
        if (historicalTrend && historicalTrend.length > 0 && typeof ApexCharts !== 'undefined') {
            const historicalTrendOptions = {
                series: [{
                    name: 'NKO',
                    data: historicalTrend.map(item => item.nilai)
                }],
                chart: {
                    type: 'line',
                    height: 300,
                    fontFamily: 'Poppins, sans-serif',
                    toolbar: {
                        show: false
                    }
                },
                colors: ['#4e73df'],
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        return val.toFixed(1) + '%';
                    },
                    style: {
                        fontSize: '12px',
                        colors: ["#304758"]
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                grid: {
                    borderColor: '#e0e0e0',
                    strokeDashArray: 5,
                },
                markers: {
                    size: 6,
                    colors: ['#fff'],
                    strokeColors: '#4e73df',
                    strokeWidth: 2
                },
                xaxis: {
                    categories: historicalTrend.map(item => item.tahun),
                    labels: {
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    min: 0,
                    max: 100,
                    labels: {
                        formatter: function(val) {
                            return val.toFixed(0) + '%';
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val.toFixed(2) + '%';
                        }
                    }
                }
            };

            document.querySelector('#historicalTrendChart .loading-chart').style.display = 'none';
            const historicalTrendChart = new ApexCharts(document.querySelector("#historicalTrendChart"), historicalTrendOptions);
            historicalTrendChart.render();
        }

        // Chart Forecast Pencapaian
        if (forecastData && forecastData.length > 0 && typeof ApexCharts !== 'undefined') {
            // Pisahkan data aktual dan forecast
            const months = forecastData.map(item => item.bulan);
            const actualData = forecastData.map(item => item.tipe === 'Aktual' ? item.nilai : null);
            const forecastValues = forecastData.map(item => item.tipe === 'Forecast' ? item.nilai : null);

            const forecastOptions = {
                series: [
                    {
                        name: 'Aktual',
                        data: actualData
                    },
                    {
                        name: 'Forecast',
                        data: forecastValues
                    }
                ],
                chart: {
                    type: 'line',
                    height: 300,
                    fontFamily: 'Poppins, sans-serif',
                    toolbar: {
                        show: false
                    }
                },
                colors: ['#4e73df', '#f6c23e'],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: [3, 3],
                    dashArray: [0, 5]
                },
                grid: {
                    borderColor: '#e0e0e0',
                    strokeDashArray: 5,
                },
                markers: {
                    size: 5,
                    colors: ['#fff', '#fff'],
                    strokeColors: ['#4e73df', '#f6c23e'],
                    strokeWidth: 2
                },
                xaxis: {
                    categories: months,
                    labels: {
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    min: 0,
                    max: 100,
                    labels: {
                        formatter: function(val) {
                            return val.toFixed(0) + '%';
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val ? val.toFixed(2) + '%' : 'Tidak ada data';
                        }
                    }
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right',
                    fontSize: '13px'
                }
            };

            document.querySelector('#forecastChart .loading-chart').style.display = 'none';
            const forecastChart = new ApexCharts(document.querySelector("#forecastChart"), forecastOptions);
            forecastChart.render();
        }
    }
</script>
@endsection
