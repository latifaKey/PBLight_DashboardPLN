@extends('layouts.app')

@section('title', 'Riwayat KPI')
@section('page_title', 'RIWAYAT DAN TREN KPI')

@section('styles')
<style>
    .kpi-history-container {
        background: var(--pln-accent-bg);
        border-radius: 16px;
        padding: 20px 25px;
        box-shadow: 0 8px 25px var(--pln-shadow);
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
        border: 1px solid var(--pln-border);
    }

    .history-banner {
        background: linear-gradient(135deg, var(--pln-blue), var(--pln-light-blue), #2b5797);
        color: white;
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 123, 255, 0.3);
        transition: all 0.3s ease;
    }

    .history-banner:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 123, 255, 0.4);
    }

    .history-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at top right, rgba(255, 255, 255, 0.2), transparent 70%);
        z-index: 1;
    }

    .history-banner-content {
        position: relative;
        z-index: 2;
    }

    .history-banner h4 {
        margin: 0;
        font-weight: 700;
        font-size: 1.6rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        position: relative;
        z-index: 2;
    }

    .history-banner h4 i {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 1.2rem;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .history-banner .banner-subtitle {
        margin-top: 10px;
        opacity: 0.9;
        font-size: 1.05rem;
        font-weight: 400;
    }

    .history-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 0.95rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
        position: relative;
        z-index: 2;
    }

    .history-badge:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    .history-badge i {
        margin-right: 8px;
    }

    .kpi-filter-container {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 20px;
        background: var(--pln-surface);
        padding: 15px;
        border-radius: 12px;
        border: 1px solid var(--pln-border);
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .data-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 20px;
    }

    .data-table th {
        background: linear-gradient(90deg, var(--pln-blue), var(--pln-light-blue));
        color: white;
        padding: 12px 15px;
        text-align: center;
        font-weight: 600;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .data-table th:first-child {
        border-top-left-radius: 8px;
        text-align: left;
        position: sticky;
        left: 0;
        z-index: 20;
    }

    .data-table th:last-child {
        border-top-right-radius: 8px;
    }

    .data-table td {
        padding: 10px 15px;
        border-bottom: 1px solid var(--pln-border);
        text-align: center;
    }

    .data-table td:first-child {
        text-align: left;
        font-weight: 600;
        background: var(--pln-surface);
        position: sticky;
        left: 0;
    }

    .data-table tr:last-child td {
        border-bottom: none;
    }

    .data-table tr:hover td {
        background-color: rgba(0, 156, 222, 0.05);
    }

    .data-table .text-success {
        color: #28a745 !important;
        font-weight: 600;
    }

    .data-table .text-warning {
        color: #ffc107 !important;
        font-weight: 600;
    }

    .data-table .text-danger {
        color: #dc3545 !important;
        font-weight: 600;
    }

    .kpi-chart-container {
        height: 400px;
        margin-top: 35px;
        margin-bottom: 35px;
        background: var(--pln-surface);
        border-radius: 16px;
        padding: 25px;
        border: 1px solid var(--pln-border);
        box-shadow: 0 10px 25px var(--pln-shadow);
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .kpi-chart-container:hover {
        box-shadow: 0 15px 30px var(--pln-shadow);
        transform: translateY(-5px);
    }

    .seasonal-label {
        position: absolute;
        top: -10px;
        left: 20px;
        background: linear-gradient(90deg, var(--pln-blue), var(--pln-light-blue));
        color: white;
        padding: 8px 20px;
        border-radius: 30px;
        font-size: 0.9rem;
        font-weight: 600;
        z-index: 5;
        box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
        letter-spacing: 0.5px;
    }

    .export-buttons {
        margin: 30px 0;
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .export-buttons .btn {
        padding: 10px 20px;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        font-weight: 600;
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
        border: none;
    }

    .export-buttons .btn-success {
        background: linear-gradient(135deg, #28a745, #20c997);
    }

    .export-buttons .btn-danger {
        background: linear-gradient(135deg, #dc3545, #e83e8c);
    }

    .export-buttons .btn-secondary {
        background: linear-gradient(135deg, #6c757d, #495057);
    }

    .export-buttons .btn i {
        margin-right: 8px;
        font-size: 1.1rem;
    }

    .export-buttons .btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    /* Styles for Statistics Cards */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: var(--pln-surface);
        border-radius: 16px;
        padding: 25px;
        border: 1px solid var(--pln-border);
        box-shadow: 0 8px 20px var(--pln-shadow);
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: linear-gradient(90deg, var(--pln-blue), var(--pln-light-blue));
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px var(--pln-shadow);
    }

    .stat-card-title {
        font-size: 1.05rem;
        margin-bottom: 15px;
        color: var(--pln-text-secondary);
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .stat-card-title i {
        margin-right: 10px;
        background: linear-gradient(135deg, var(--pln-blue), var(--pln-light-blue));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-size: 1.2rem;
    }

    .stat-card-value {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 15px;
        color: var(--pln-blue);
        line-height: 1.1;
    }

    .stat-card-desc {
        font-size: 0.95rem;
        color: var(--pln-text-secondary);
        margin-top: auto;
        line-height: 1.5;
    }

    .progress-bar {
        height: 10px;
        width: 100%;
        background: var(--pln-border);
        border-radius: 8px;
        margin-top: 15px;
        overflow: hidden;
        box-shadow: inset 0 2px 5px rgba(0,0,0,0.1);
    }

    .progress-bar-filled {
        height: 100%;
        border-radius: 8px;
        transition: width 1s ease;
    }

    .progress-success {
        background: linear-gradient(90deg, #28a745, #20c997);
    }

    .progress-warning {
        background: linear-gradient(90deg, #ffc107, #fd7e14);
    }

    .progress-danger {
        background: linear-gradient(90deg, #dc3545, #e83e8c);
    }

    .stats-bidang-container {
        margin-top: 20px;
        background: var(--pln-surface);
        border-radius: 16px;
        padding: 25px;
        border: 1px solid var(--pln-border);
        box-shadow: 0 8px 20px var(--pln-shadow);
        position: relative;
        overflow: hidden;
    }

    .stats-bidang-container::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(0, 156, 222, 0.08), transparent 70%);
        border-radius: 50%;
    }

    .stats-bidang-title {
        font-size: 1.2rem;
        margin-bottom: 20px;
        font-weight: 600;
        color: var(--pln-blue);
        display: flex;
        align-items: center;
        position: relative;
    }

    .stats-bidang-title::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -8px;
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, var(--pln-blue), var(--pln-light-blue));
        border-radius: 3px;
    }

    .stats-bidang-title i {
        margin-right: 10px;
        background: var(--pln-light-blue);
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        box-shadow: 0 4px 8px rgba(0, 156, 222, 0.3);
    }

    .bidang-stats-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 15px;
    }

    .bidang-stat-item {
        padding: 18px;
        background: var(--pln-accent-bg);
        border-radius: 12px;
        border-left: 4px solid var(--pln-blue);
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
    }

    .bidang-stat-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        border-left-color: var(--pln-light-blue);
    }

    .bidang-stat-item::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        width: 30px;
        height: 30px;
        background: linear-gradient(135deg, transparent, rgba(0, 156, 222, 0.1));
        border-radius: 0 0 12px 0;
    }

    .bidang-name {
        font-weight: 600;
        margin-bottom: 12px;
        color: var(--pln-text);
        display: flex;
        align-items: center;
        position: relative;
    }

    .bidang-name::before {
        content: '';
        display: inline-block;
        width: 8px;
        height: 8px;
        background: var(--pln-light-blue);
        border-radius: 50%;
        margin-right: 8px;
        box-shadow: 0 0 0 2px rgba(0, 156, 222, 0.2);
    }

    .bidang-metrics {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        font-size: 0.9rem;
    }

    .bidang-metric {
        background: var(--pln-surface);
        padding: 6px 10px;
        border-radius: 8px;
        white-space: nowrap;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        transition: all 0.2s ease;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .bidang-metric:hover {
        background: rgba(0, 156, 222, 0.1);
        transform: translateY(-2px);
    }

    .bidang-metric i {
        margin-right: 6px;
        color: var(--pln-light-blue);
        font-size: 0.8rem;
    }

    .link-to-current {
        margin-bottom: 25px;
        background: linear-gradient(to right, rgba(0, 156, 222, 0.1), rgba(0, 156, 222, 0.05));
        border-radius: 12px;
        padding: 20px;
        border: 1px dashed rgba(0, 156, 222, 0.3);
        position: relative;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03);
    }

    .link-to-current:hover {
        background: linear-gradient(to right, rgba(0, 156, 222, 0.15), rgba(0, 156, 222, 0.07));
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    }

    .link-to-current i {
        color: var(--pln-blue);
        font-size: 1.2rem;
    }

    .link-to-current a {
        transition: all 0.3s ease;
    }

    .link-to-current a:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 10px rgba(0, 123, 255, 0.2);
    }

    .table-responsive {
        overflow-x: auto;
        max-height: 650px;
        position: relative;
        border-radius: 12px;
        box-shadow: 0 8px 25px var(--pln-shadow);
        margin: 30px 0;
    }

    .table-responsive::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: rgba(0,0,0,0.05);
        border-radius: 10px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: var(--pln-light-blue);
        border-radius: 10px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: var(--pln-blue);
    }

    .data-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
    }

    .data-table th {
        background: linear-gradient(90deg, var(--pln-blue), var(--pln-light-blue));
        color: white;
        padding: 15px;
        text-align: center;
        font-weight: 600;
        position: sticky;
        top: 0;
        z-index: 10;
        white-space: nowrap;
    }

    .data-table th:first-child {
        border-top-left-radius: 12px;
        text-align: left;
        position: sticky;
        left: 0;
        z-index: 20;
    }

    .data-table th:last-child {
        border-top-right-radius: 12px;
    }

    .data-table td {
        padding: 12px 15px;
        border-bottom: 1px solid var(--pln-border);
        text-align: center;
        vertical-align: middle;
        transition: all 0.2s ease;
    }

    .data-table td:first-child {
        text-align: left;
        font-weight: 600;
        background: var(--pln-surface);
        position: sticky;
        left: 0;
        z-index: 5;
    }

    .data-table td .btn {
        border-radius: 8px;
        padding: 5px 10px;
        transition: all 0.3s ease;
    }

    .data-table td .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 10px rgba(0,0,0,0.1);
    }

    .data-table tr:last-child td {
        border-bottom: none;
    }

    .data-table tr:hover td {
        background-color: rgba(0, 156, 222, 0.05);
    }

    .data-table .text-success {
        color: #28a745 !important;
        font-weight: 600;
    }

    .data-table .text-warning {
        color: #ffc107 !important;
        font-weight: 600;
    }

    .data-table .text-danger {
        color: #dc3545 !important;
        font-weight: 600;
    }

    .data-table i.fa-check-circle {
        margin-left: 5px;
        font-size: 0.9rem;
        animation: pulse 1.5s infinite ease-in-out;
    }

    @keyframes pulse {
        0% { opacity: 0.6; }
        50% { opacity: 1; }
        100% { opacity: 0.6; }
    }

    .pagination-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 25px 0;
        background: var(--pln-surface);
        padding: 15px 20px;
        border-radius: 12px;
        box-shadow: 0 5px 15px var(--pln-shadow);
        border: 1px solid var(--pln-border);
    }

    .showing-info {
        font-size: 0.95rem;
        color: var(--pln-text-secondary);
    }

    .showing-info span {
        font-weight: 600;
        color: var(--pln-blue);
    }

    .page-buttons {
        display: flex;
        gap: 10px;
    }

    .btn-page {
        background: var(--pln-surface);
        border: 1px solid var(--pln-border);
        border-radius: 8px;
        padding: 10px 20px;
        color: var(--pln-text);
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-page:hover {
        background: var(--pln-light-blue);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 123, 255, 0.2);
    }

    .btn-page.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .btn-page.disabled:hover {
        background: var(--pln-surface);
        color: var(--pln-text);
        transform: none;
        box-shadow: none;
    }
</style>
@endsection

@section('content')
<div class="kpi-history-container">
    <div class="history-banner">
        <div class="history-banner-content">
            <h4><i class="fas fa-chart-line"></i>Analisis Riwayat & Tren KPI</h4>
            <div class="banner-subtitle">Evaluasi performa sepanjang periode {{ $tahun }}</div>
        </div>
        <div class="history-badge">
            <i class="fas fa-history"></i> Tren dan perbandingan antar periode
        </div>
    </div>

    <div class="link-to-current">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Halaman ini menampilkan data historis dan tren KPI sepanjang periode.</strong>
        Untuk melihat <strong>status KPI terkini</strong>, silakan kunjungi
        <a href="{{ route('kpi.index') }}" class="btn btn-sm btn-primary ms-2">
            <i class="fas fa-tasks me-1"></i> Laporan Status KPI
        </a>
    </div>

    <div class="kpi-filter-container">
        <div class="filter-group">
            <form method="GET" action="{{ route('kpi.history') }}" class="d-flex align-items-center">
                <label for="tahun" class="me-2">Tahun:</label>
                <select name="tahun" id="tahun" class="form-control me-2" style="width: 120px;">
                    @for($i = date('Y'); $i >= 2020; $i--)
                        <option value="{{ $i }}" {{ request('tahun', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
            </form>
        </div>

        <div class="filter-group">
            <label for="showPerPage" class="me-2">Tampilkan:</label>
            <select id="showPerPage" class="form-control" style="width: 100px;">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
    </div>

    @if(isset($statistics))
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-card-title">
                <i class="fas fa-chart-line"></i>Rata-rata Pencapaian
            </div>
            <div class="stat-card-value">{{ $statistics['performa_ratarata'] }}%</div>
            <div class="stat-card-desc">
                Rata-rata pencapaian semua indikator KPI sepanjang periode {{ $tahun }}
            </div>
            <div class="progress-bar">
                @php
                    $progressClass = 'progress-danger';
                    if ($statistics['performa_ratarata'] >= 80) {
                        $progressClass = 'progress-success';
                    } elseif ($statistics['performa_ratarata'] >= 60) {
                        $progressClass = 'progress-warning';
                    }
                @endphp
                <div class="progress-bar-filled {{ $progressClass }}" style="width: {{ $statistics['performa_ratarata'] }}%"></div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-title">
                <i class="fas fa-tasks"></i>Indikator Tercapai
            </div>
            <div class="stat-card-value">{{ $statistics['indikator_tercapai'] }}/{{ $statistics['total_indikator'] }}</div>
            <div class="stat-card-desc">
                Jumlah indikator yang mencapai target (>= 80%) selama periode {{ $tahun }}
            </div>
            <div class="progress-bar">
                @php
                    $progressClass = 'progress-danger';
                    if ($statistics['persentase_tercapai'] >= 80) {
                        $progressClass = 'progress-success';
                    } elseif ($statistics['persentase_tercapai'] >= 60) {
                        $progressClass = 'progress-warning';
                    }
                @endphp
                <div class="progress-bar-filled {{ $progressClass }}" style="width: {{ $statistics['persentase_tercapai'] }}%"></div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-title">
                <i class="fas fa-check-circle"></i>Terverifikasi
            </div>
            <div class="stat-card-value">{{ $statistics['persentase_diverifikasi'] }}%</div>
            <div class="stat-card-desc">
                Persentase data yang sudah diverifikasi dari seluruh data KPI
            </div>
            <div class="progress-bar">
                @php
                    $progressClass = 'progress-danger';
                    if ($statistics['persentase_diverifikasi'] >= 80) {
                        $progressClass = 'progress-success';
                    } elseif ($statistics['persentase_diverifikasi'] >= 60) {
                        $progressClass = 'progress-warning';
                    }
                @endphp
                <div class="progress-bar-filled {{ $progressClass }}" style="width: {{ $statistics['persentase_diverifikasi'] }}%"></div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-title">
                <i class="fas fa-calendar-alt"></i>Tahun Data
            </div>
            <div class="stat-card-value">{{ $tahun }}</div>
            <div class="stat-card-desc">
                Analisis KPI tahun {{ $tahun }} menampilkan tren performa sepanjang periode
            </div>
        </div>
    </div>

    @if(isset($statistics['bidang_stats']))
    <div class="stats-bidang-container">
        <div class="stats-bidang-title">
            <i class="fas fa-building"></i>Performa per Bidang
        </div>
        <div class="bidang-stats-list">
            @php
                // Pastikan bidang_stats selalu berupa array dan bukan objek collection
                $bidangStatsArr = is_array($statistics['bidang_stats']) ?
                    $statistics['bidang_stats'] :
                    (method_exists($statistics['bidang_stats'], 'toArray') ?
                        $statistics['bidang_stats']->toArray() :
                        (array)$statistics['bidang_stats']);
            @endphp

            @foreach($bidangStatsArr as $bidangStat)
            <div class="bidang-stat-item">
                <div class="bidang-name">{{ $bidangStat['bidang'] }}</div>
                <div class="bidang-metrics">
                    <span class="bidang-metric">
                        <i class="fas fa-chart-line"></i> {{ $bidangStat['performa_ratarata'] }}%
                    </span>
                    <span class="bidang-metric">
                        <i class="fas fa-tasks"></i> {{ $bidangStat['indikator_tercapai'] }}/{{ $bidangStat['total_indikator'] }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    @endif

    <div class="export-buttons">
        <button class="btn btn-success" onclick="exportToExcel()">
            <i class="fas fa-file-excel"></i> Export Excel
        </button>
        <button class="btn btn-danger" onclick="exportToPdf()">
            <i class="fas fa-file-pdf"></i> Export PDF
        </button>
        <a href="{{ route('kpi.index') }}" class="btn btn-secondary">
            <i class="fas fa-external-link-alt"></i> Lihat Status Saat Ini
        </a>
    </div>

    @if(isset($pilars))
        <div class="kpi-chart-container position-relative">
            <div class="seasonal-label"><i class="fas fa-chart-line mr-2"></i> Tren Bulanan</div>
            <canvas id="kpiTrendChart"></canvas>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Indikator</th>
                        <th>Jan</th>
                        <th>Feb</th>
                        <th>Mar</th>
                        <th>Apr</th>
                        <th>Mei</th>
                        <th>Jun</th>
                        <th>Jul</th>
                        <th>Agu</th>
                        <th>Sep</th>
                        <th>Okt</th>
                        <th>Nov</th>
                        <th>Des</th>
                        <th>Rata-rata</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pilars as $pilar)
                        <tr>
                            <td colspan="14" style="background: #f8f9fa; font-weight: bold; color: var(--pln-blue);">
                                {{ $pilar->kode }}. {{ $pilar->nama }}
                            </td>
                        </tr>
                        @foreach($pilar->indikators as $indikator)
                            <tr>
                                <td style="padding-left: 25px;">{{ $indikator->kode }}. {{ $indikator->nama }}</td>
                                @php
                                    $total = 0;
                                    $count = 0;
                                @endphp
                                @foreach($indikator->historiData as $data)
                                    @php
                                        $nilai = $data['nilai'];
                                        $class = '';
                                        if($nilai >= 80) $class = 'text-success';
                                        elseif($nilai >= 60) $class = 'text-warning';
                                        elseif($nilai > 0) $class = 'text-danger';

                                        if($nilai > 0) {
                                            $total += $nilai;
                                            $count++;
                                        }
                                    @endphp
                                    <td class="{{ $class }}">
                                        {{ $nilai > 0 ? number_format($nilai, 2).'%' : '-' }}
                                        @if($data['diverifikasi'] && $nilai > 0)
                                            <i class="fas fa-check-circle text-success" title="Terverifikasi"></i>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="font-weight-bold">
                                    {{ $count > 0 ? number_format($total / $count, 2).'%' : '-' }}
                                    <a href="{{ route('kpi.detail.riwayat', ['indikatorId' => $indikator->id, 'tahun' => $tahun]) }}" class="btn btn-sm btn-outline-primary ms-2" title="Lihat Detail">
                                        <i class="fas fa-search"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pagination-controls">
            <div class="showing-info">
                Menampilkan <span id="showingStart">1</span> sampai <span id="showingEnd">10</span> dari <span id="totalItems">{{ $pilars->sum(function($pilar) { return $pilar->indikators->count(); }) }}</span> indikator
            </div>
            <div class="page-buttons">
                <button class="btn-page" id="prevPage" onclick="changePage(-1)">
                    <i class="fas fa-chevron-left"></i> Sebelumnya
                </button>
                <button class="btn-page" id="nextPage" onclick="changePage(1)">
                    Selanjutnya <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> Tidak ada data riwayat KPI untuk ditampilkan.
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi pagination
        let currentPage = 1;
        const itemsPerPage = parseInt(document.getElementById('showPerPage').value);
        updatePagination();

        // Inisialisasi chart jika ada data
        @if(isset($pilars) && $pilars->isNotEmpty())
            initializeChart();
        @endif

        // Listener untuk perubahan jumlah item per halaman
        document.getElementById('showPerPage').addEventListener('change', function() {
            currentPage = 1;
            updatePagination();
        });

        // Animasi progress bars
        const progressBars = document.querySelectorAll('.progress-bar-filled');
        setTimeout(() => {
            progressBars.forEach(bar => {
                bar.style.width = bar.getAttribute('style').split(':')[1];
            });
        }, 300);
    });

    function updatePagination() {
        const itemsPerPage = parseInt(document.getElementById('showPerPage').value);
        const rows = Array.from(document.querySelectorAll('.data-table tbody tr'));
        const totalItems = rows.length;

        const start = (currentPage - 1) * itemsPerPage;
        const end = Math.min(start + itemsPerPage, totalItems);

        // Update tampilan info pagination
        document.getElementById('showingStart').textContent = start + 1;
        document.getElementById('showingEnd').textContent = end;
        document.getElementById('totalItems').textContent = totalItems;

        // Sembunyikan/tampilkan baris sesuai pagination
        rows.forEach((row, index) => {
            if (index >= start && index < end) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        // Update status tombol
        document.getElementById('prevPage').classList.toggle('disabled', currentPage === 1);
        document.getElementById('nextPage').classList.toggle('disabled', end === totalItems);
    }

    function changePage(direction) {
        const itemsPerPage = parseInt(document.getElementById('showPerPage').value);
        const rows = document.querySelectorAll('.data-table tbody tr');
        const totalPages = Math.ceil(rows.length / itemsPerPage);

        const newPage = currentPage + direction;
        if (newPage > 0 && newPage <= totalPages) {
            currentPage = newPage;
            updatePagination();
        }
    }

    function initializeChart() {
        const ctx = document.getElementById('kpiTrendChart').getContext('2d');

        // Mengumpulkan data untuk chart dari tabel
        const datasets = [];
        const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        // Warna modern dan menarik untuk setiap pilar
        const colors = [
            { bg: 'rgba(0, 123, 255, 0.7)', border: 'rgba(0, 123, 255, 1)' },
            { bg: 'rgba(40, 167, 69, 0.7)', border: 'rgba(40, 167, 69, 1)' },
            { bg: 'rgba(255, 193, 7, 0.7)', border: 'rgba(255, 193, 7, 1)' },
            { bg: 'rgba(220, 53, 69, 0.7)', border: 'rgba(220, 53, 69, 1)' },
            { bg: 'rgba(23, 162, 184, 0.7)', border: 'rgba(23, 162, 184, 1)' },
            { bg: 'rgba(153, 102, 255, 0.7)', border: 'rgba(153, 102, 255, 1)' },
            { bg: 'rgba(111, 66, 193, 0.7)', border: 'rgba(111, 66, 193, 1)' },
            { bg: 'rgba(255, 109, 109, 0.7)', border: 'rgba(255, 109, 109, 1)' }
        ];

        @if(isset($pilars))
            @foreach($pilars as $index => $pilar)
                // Rata-rata bulanan per pilar
                const pilarData = Array(12).fill(0);
                const pilarCounts = Array(12).fill(0);

                @foreach($pilar->indikators as $indikator)
                    @foreach($indikator->historiData as $idx => $data)
                        pilarData[{{ $idx }}] += {{ $data['nilai'] }};
                        if ({{ $data['nilai'] }} > 0) {
                            pilarCounts[{{ $idx }}]++;
                        }
                    @endforeach
                @endforeach

                // Hitung rata-rata
                const avgData = pilarData.map((value, index) =>
                    pilarCounts[index] > 0 ? value / pilarCounts[index] : null
                );

                datasets.push({
                    label: '{{ $pilar->nama }}',
                    data: avgData,
                    borderColor: colors[{{ $index % count(colors) }}].border,
                    backgroundColor: colors[{{ $index % count(colors) }}].bg,
                    tension: 0.4,
                    fill: false,
                    pointRadius: 5,
                    pointHoverRadius: 8,
                    borderWidth: 3,
                    pointBackgroundColor: colors[{{ $index % count(colors) }}].border,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointShadowOffsetX: 1,
                    pointShadowOffsetY: 1,
                    pointShadowBlur: 5,
                    pointShadowColor: 'rgba(0,0,0,0.2)'
                });
            @endforeach
        @endif

        // Tambahkan garis target 80%
        datasets.push({
            label: 'Target Minimal (80%)',
            data: Array(12).fill(80),
            borderColor: 'rgba(255, 99, 132, 0.7)',
            borderWidth: 2,
            borderDash: [5, 5],
            pointRadius: 0,
            fill: false
        });

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1500,
                    easing: 'easeOutQuart'
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Tren Pencapaian KPI per Pilar ({{ request('tahun', date('Y')) }})',
                        font: {
                            size: 18,
                            weight: 'bold'
                        },
                        padding: {
                            top: 10,
                            bottom: 25
                        },
                        color: '#333'
                    },
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + (context.parsed.y ? context.parsed.y.toFixed(2) + '%' : 'Tidak ada data');
                            }
                        },
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 13
                        },
                        padding: 12,
                        cornerRadius: 8,
                        titleColor: 'rgba(255,255,255,0.9)',
                        bodyColor: 'rgba(255,255,255,0.9)',
                        borderColor: 'rgba(255,255,255,0.1)',
                        borderWidth: 1
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        title: {
                            display: true,
                            text: 'Persentase Pencapaian (%)',
                            font: {
                                weight: 'bold',
                                size: 13
                            },
                            padding: {
                                bottom: 10
                            }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)',
                            lineWidth: 1,
                            borderDash: [3, 3]
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Bulan',
                            font: {
                                weight: 'bold',
                                size: 13
                            },
                            padding: {
                                top: 10
                            }
                        },
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                elements: {
                    line: {
                        tension: 0.4,
                        capBezierPoints: true
                    }
                }
            }
        });
    }

    function exportToExcel() {
        // Implementasi fungsi ekspor ke excel
        const tahun = {{ request('tahun', date('Y')) }};
        window.location.href = `{{ route('kpi.export.excel') }}?tahun=${tahun}`;
    }

    function exportToPdf() {
        // Implementasi fungsi ekspor ke PDF
        const tahun = {{ request('tahun', date('Y')) }};
        window.location.href = `{{ route('kpi.export.pdf') }}?tahun=${tahun}`;
    }
</script>
@endsection
