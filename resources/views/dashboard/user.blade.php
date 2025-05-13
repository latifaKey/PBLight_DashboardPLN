{{-- resources/views/dashboard/user.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Kinerja - Karyawan')
@section('page_title', 'DASHBOARD KARYAWAN')

@section('styles')
<style>
  .dashboard-content {
    max-width: 1800px;
    margin: 0 auto;
  }

  /* Stat Cards */
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

  .stat-card {
    background: var(--pln-accent-bg);
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 25px;
    position: relative;
    overflow: hidden;
    border: 1px solid var(--pln-border);
    box-shadow: 0 8px 20px var(--pln-shadow);
    transition: all 0.3s ease;
  }

  .stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px var(--pln-shadow);
  }

  .stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, var(--pln-blue), var(--pln-light-blue));
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
    color: var(--pln-text);
    margin: 0;
  }

  .stat-icon {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--pln-blue), var(--pln-light-blue));
    color: white;
    font-size: 20px;
    box-shadow: 0 5px 15px rgba(0, 156, 222, 0.3);
  }

  .stat-value {
    font-size: 28px;
    font-weight: 700;
    color: var(--pln-text);
    margin: 15px 0 5px;
  }

  .stat-description {
    font-size: 13px;
    color: var(--pln-text-secondary);
    margin: 0;
  }

  /* Gauge Meter */
  .meter-container {
    position: relative;
    width: 300px;
    height: 200px;
    margin: 0 auto 20px;
    transition: all 0.3s ease;
  }

  .meter-container:hover {
    transform: translateY(-5px);
  }

  .nko-value {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    text-align: center;
    font-size: 28px;
    font-weight: 700;
    color: var(--pln-text);
    text-shadow: 0 2px 5px var(--pln-shadow);
    transition: all 0.3s ease;
  }

  .nko-label {
    position: absolute;
    top: 40%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 22px;
    font-weight: 600;
    color: var(--pln-light-blue);
    text-shadow: 0 2px 5px var(--pln-shadow);
    letter-spacing: 1px;
  }

  /* Chart Container */
  .chart-container {
    background: var(--pln-accent-bg);
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 25px;
    transition: all 0.3s ease;
    border: 1px solid var(--pln-border);
    box-shadow: 0 8px 20px var(--pln-shadow);
    position: relative;
    overflow: hidden;
    max-height: 350px;
    overflow-y: hidden;
  }

  .chart-container::-webkit-scrollbar {
    width: 8px;
  }

  .chart-container::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.1);
    border-radius: 10px;
  }

  .chart-container::-webkit-scrollbar-thumb {
    background: var(--pln-light-blue);
    border-radius: 10px;
  }

  .chart-title {
    font-size: 18px;
    color: var(--pln-light-blue);
    margin-bottom: 20px;
    font-weight: 600;
  }

  /* Progress */
  .progress {
    height: 10px;
    background-color: rgba(255,255,255,0.1);
    margin: 15px 0;
    border-radius: 5px;
    overflow: hidden;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.2);
  }

  [data-theme="light"] .progress {
    background-color: rgba(0,0,0,0.1);
  }

  .progress-bar {
    height: 100%;
    border-radius: 5px;
    transition: width 0.5s ease-in-out;
    background-size: 15px 15px;
    animation: none;
  }

  .progress-red {
    background-color: #F44336;
  }

  .progress-yellow {
    background-color: #FFC107;
  }

  .progress-green {
    background-color: #4CAF50;
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
    .bidang-grid {
      grid-template-columns: 1fr;
    }

    .chart-container, .bidang-grid-container {
      max-height: 450px;
    }
  }

  /* Tabs Styling */
  .nav-tabs {
    border-bottom: 1px solid var(--pln-border);
    margin-bottom: 25px;
  }

  .nav-tabs .nav-link {
    color: var(--pln-text-secondary);
    border: none;
    border-bottom: 3px solid transparent;
    background: transparent;
    padding: 12px 15px;
    font-weight: 600;
    transition: all 0.3s ease;
  }

  .nav-tabs .nav-link:hover {
    color: var(--pln-light-blue);
  }

  .nav-tabs .nav-link.active {
    color: var(--pln-light-blue);
    border-bottom-color: var(--pln-light-blue);
    background: transparent;
  }

  .tab-content {
    padding-top: 15px;
  }

  /* Bidang Grid Container */
  .bidang-grid-container {
    background: var(--pln-accent-bg);
    border-radius: 16px;
    padding: 25px;
    transition: all 0.3s ease;
    border: 1px solid var(--pln-border);
    box-shadow: 0 8px 20px var(--pln-shadow);
    position: relative;
    overflow: hidden;
    margin-top: 30px;
    max-height: 600px;
    overflow-y: auto;
  }

  .bidang-grid-container::-webkit-scrollbar {
    width: 8px;
  }

  .bidang-grid-container::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.1);
    border-radius: 10px;
  }

  .bidang-grid-container::-webkit-scrollbar-thumb {
    background: var(--pln-light-blue);
    border-radius: 10px;
  }

  .bidang-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
  }

  .bidang-grid-title {
    font-size: 18px;
    color: var(--pln-light-blue);
    margin-bottom: 20px;
    font-weight: 600;
  }

  .pagination-container {
    display: flex;
    justify-content: center;
    margin-top: 20px;
  }

  .pagination {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .pagination li {
    margin: 0 5px;
  }

  .pagination a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: var(--pln-accent-bg);
    color: var(--pln-text);
    text-decoration: none;
    font-weight: 600;
    border: 1px solid var(--pln-border);
    transition: all 0.3s ease;
  }

  .pagination a:hover,
  .pagination a.active {
    background: var(--pln-light-blue);
    color: white;
  }

  .bidang-card {
    background: var(--pln-surface);
    border-radius: 12px;
    padding: 16px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 10px var(--pln-shadow);
    border: 1px solid var(--pln-border);
    height: 220px;
    display: flex;
    flex-direction: column;
  }

  .bidang-card canvas {
    margin-top: auto;
    max-height: 80px !important;
  }

  .bidang-title {
    font-size: 15px;
    font-weight: 600;
    color: var(--pln-text);
    margin-bottom: 10px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .bidang-value {
    font-size: 22px;
    font-weight: 700;
    color: var(--pln-light-blue);
    margin-bottom: 5px;
  }

  /* Optimasi rendering */
  .chart-container canvas,
  .bidang-card canvas {
    will-change: transform;
    transform: translateZ(0);
    backface-visibility: hidden;
  }

  /* Media queries untuk responsif */
  @media (max-width: 576px) {
    .meter-container {
      width: 200px;
      height: 150px;
    }

    .nko-label {
      font-size: 18px;
    }

    .nko-value {
      font-size: 22px;
    }

    .bidang-grid-container {
      padding: 15px;
      max-height: 500px;
    }

    .bidang-card {
      height: 180px;
    }

    .chart-container {
      padding: 15px;
      max-height: 300px;
    }
  }

  /* Mencegah glitch dan flickering */
  * {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
  }

  /* Tambahan gaya untuk komponen terintegrasi */
  .notification-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
    max-height: 330px;
    overflow-y: auto;
  }

  .notification-item {
    display: flex;
    background: var(--pln-surface);
    border-radius: 12px;
    padding: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.2s ease;
  }

  .notification-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  }

  .notification-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    color: white;
  }

  .bg-info {
    background: var(--pln-light-blue);
  }

  .bg-success {
    background: #4CAF50;
  }

  .bg-warning {
    background: #FFC107;
  }

  .bg-danger {
    background: #F44336;
  }

  .notification-content {
    flex: 1;
  }

  .notification-title {
    font-size: 16px;
    font-weight: 600;
    margin: 0 0 5px;
    color: var(--pln-text);
  }

  .notification-message {
    font-size: 14px;
    color: var(--pln-text-secondary);
    margin: 0 0 5px;
  }

  .notification-time {
    font-size: 12px;
    color: var(--pln-text-secondary);
    font-style: italic;
  }

  /* Change list styles */
  .change-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
    max-height: 330px;
    overflow-y: auto;
  }

  .change-item {
    background: var(--pln-surface);
    border-radius: 12px;
    padding: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.2s ease;
  }

  .change-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  }

  .change-bidang {
    font-size: 16px;
    font-weight: 600;
    color: var(--pln-text);
    margin-bottom: 10px;
  }

  .change-values {
    display: flex;
    align-items: center;
    margin-bottom: 5px;
  }

  .change-current {
    font-weight: 600;
    font-size: 18px;
    color: var(--pln-text);
  }

  .change-arrow {
    margin: 0 8px;
    font-size: 14px;
  }

  .change-arrow.positive {
    color: #4CAF50;
  }

  .change-arrow.negative {
    color: #F44336;
  }

  .change-previous {
    font-size: 14px;
    color: var(--pln-text-secondary);
  }

  .change-diff {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 8px;
  }

  .text-success {
    color: #4CAF50;
  }

  .text-danger {
    color: #F44336;
  }

  /* Best list styles */
  .best-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
    max-height: 330px;
    overflow-y: auto;
  }

  .best-item {
    background: var(--pln-surface);
    border-radius: 12px;
    padding: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.2s ease;
  }

  .best-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  }

  .best-badge {
    float: right;
    font-size: 16px;
    font-weight: 600;
    color: white;
    background: #4CAF50;
    padding: 4px 10px;
    border-radius: 8px;
    margin-left: 10px;
  }

  .best-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--pln-text);
    margin: 0 0 5px;
  }

  .best-bidang {
    font-size: 14px;
    color: var(--pln-text-secondary);
    margin: 0 0 10px;
  }

  /* Empty state */
  .empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 30px;
    text-align: center;
    color: var(--pln-text-secondary);
  }

  .empty-icon {
    font-size: 40px;
    margin-bottom: 15px;
    opacity: 0.6;
  }

  /* Bidang Grid styles */
  .bidang-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
  }

  .bidang-card {
    background: var(--pln-surface);
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
  }

  .bidang-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
  }

  .bidang-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
  }

  .bidang-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--pln-text);
    margin: 0;
  }

  .bidang-progress {
    margin: 15px 0;
  }

  .bidang-status {
    margin-bottom: 15px;
  }

  .status {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
  }

  .status-success {
    background-color: rgba(76, 175, 80, 0.15);
    color: #4CAF50;
  }

  .status-good {
    background-color: rgba(33, 150, 243, 0.15);
    color: #2196F3;
  }

  .status-medium {
    background-color: rgba(255, 193, 7, 0.15);
    color: #FFC107;
  }

  .status-poor {
    background-color: rgba(244, 67, 54, 0.15);
    color: #F44336;
  }

  /* Button styles */
  .btn-action {
    background: var(--pln-light-blue);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 8px 16px;
    font-weight: 600;
    transition: all 0.2s ease;
  }

  .btn-action:hover {
    background: var(--pln-blue);
    color: white;
    transform: translateY(-2px);
  }

  .btn-action.btn-sm {
    padding: 5px 10px;
    font-size: 12px;
  }
</style>
@endsection

@section('content')
<div class="dashboard-content">
    <!-- Filter dan Periode -->
    <div class="filter-row row mb-4">
        <div class="col-md-8">
            <form action="{{ route('dashboard') }}" method="GET" class="d-flex">
                <div class="form-group mr-3">
                    <label for="tahun">Tahun</label>
                    <select class="form-control" id="tahun" name="tahun" onchange="this.form.submit()">
                        @for ($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="form-group">
                    <label for="bulan">Bulan</label>
                    <select class="form-control" id="bulan" name="bulan" onchange="this.form.submit()">
                        @php
                            $namaBulan = [
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ];
                        @endphp
                        @foreach ($namaBulan as $key => $nama)
                            <option value="{{ $key }}" {{ $bulan == $key ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Row 1: Statistik Bidang -->
    <div class="dashboard-row">
        <div class="dashboard-col">
            <div class="stat-card">
                <div class="stat-header">
                    <h2 class="stat-title">Performa Tertinggi</h2>
                    <div class="stat-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                </div>
                <h3 class="stat-value">{{ $bestPerformer['nama'] }}</h3>
                <p class="stat-description">{{ $bestPerformer['nilai'] }}% pencapaian</p>
            </div>
        </div>
        <div class="dashboard-col">
            <div class="stat-card">
                <div class="stat-header">
                    <h2 class="stat-title">Performa Terendah</h2>
                    <div class="stat-icon">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                </div>
                <h3 class="stat-value">{{ $worstPerformer['nama'] }}</h3>
                <p class="stat-description">{{ $worstPerformer['nilai'] }}% pencapaian</p>
            </div>
        </div>
        <div class="dashboard-col">
            <div class="stat-card">
                <div class="stat-header">
                    <h2 class="stat-title">Rata-rata Pencapaian</h2>
                    <div class="stat-icon">
                        <i class="fas fa-percentage"></i>
                    </div>
                </div>
                @php
                    $nilaiArray = array_column($bidangData, 'nilai');
                    $avgValue = count($nilaiArray) > 0 ? array_sum($nilaiArray) / count($nilaiArray) : 0;
                @endphp
                <h3 class="stat-value">{{ number_format($avgValue, 2) }}%</h3>
                <p class="stat-description">Seluruh bidang</p>
            </div>
        </div>
        <div class="dashboard-col">
            <div class="stat-card">
                <div class="stat-header">
                    <h2 class="stat-title">Total Bidang</h2>
                    <div class="stat-icon">
                        <i class="fas fa-building"></i>
                    </div>
                </div>
                <h3 class="stat-value">{{ count($bidangData) }}</h3>
                <p class="stat-description">Jumlah bidang dalam organisasi</p>
            </div>
        </div>
    </div>

    <!-- Row 2: Chart dan Notifikasi -->
    <div class="row mt-4">
        <div class="col-md-7">
            <div class="chart-container">
                <h2 class="chart-title">Pencapaian Per-Bidang</h2>
                <canvas id="bidangChart" height="250"></canvas>
            </div>
        </div>
        <div class="col-md-5">
            <div class="dashboard-card">
                <h2 class="card-title">
                    <i class="fas fa-bell mr-2"></i>
                    Notifikasi Terbaru
                </h2>

                @if($latestNotifications->count() > 0)
                    <div class="notification-list">
                        @foreach($latestNotifications as $notification)
                            <div class="notification-item">
                                <div class="notification-icon bg-{{ $notification->jenis }}">
                                    <i class="fas fa-{{ $notification->jenis == 'info' ? 'info-circle' : ($notification->jenis == 'success' ? 'check-circle' : 'exclamation-circle') }}"></i>
                                </div>
                                <div class="notification-content">
                                    <h4 class="notification-title">{{ $notification->judul }}</h4>
                                    <p class="notification-message">{{ $notification->pesan }}</p>
                                    <div class="notification-time">{{ $notification->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('notifikasi.index') }}" class="btn btn-action">Lihat Semua Notifikasi</a>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-bell-slash empty-icon"></i>
                        <p>Tidak ada notifikasi baru</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Row 3: Perubahan Bulanan & Indikator Terbaik -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="dashboard-card">
                <h2 class="card-title">
                    <i class="fas fa-exchange-alt mr-2"></i>
                    Perubahan dari Bulan Lalu
                </h2>

                <div class="change-list">
                    @foreach($monthlyChange as $change)
                        <div class="change-item">
                            <div class="change-bidang">{{ $change['nama'] }}</div>
                            <div class="change-values">
                                <div class="change-current">{{ $change['current'] }}%</div>
                                <div class="change-arrow {{ $change['change'] >= 0 ? 'positive' : 'negative' }}">
                                    <i class="fas fa-{{ $change['change'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                </div>
                                <div class="change-previous">{{ $change['previous'] }}%</div>
                            </div>
                            <div class="change-diff {{ $change['change'] >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ $change['change'] >= 0 ? '+' : '' }}{{ $change['change'] }}%
                            </div>
                            <div class="progress">
                                <div class="progress-bar {{ $change['change'] >= 0 ? 'progress-green' : 'progress-red' }}"
                                    style="width: {{ min(100, abs($change['change'])) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="dashboard-card">
                <h2 class="card-title">
                    <i class="fas fa-star mr-2"></i>
                    Indikator dengan Kinerja Terbaik
                </h2>

                <div class="best-list">
                    @foreach($bestIndikators as $indikator)
                        <div class="best-item">
                            <div class="best-badge">{{ $indikator->persentase }}%</div>
                            <div class="best-content">
                                <h4 class="best-title">{{ $indikator->indikator->kode }} - {{ $indikator->indikator->nama }}</h4>
                                <p class="best-bidang">{{ $indikator->indikator->bidang->nama }}</p>
                            </div>
                            <div class="progress">
                                <div class="progress-bar progress-green"
                                    style="width: {{ $indikator->persentase }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Row 4: Tabel Bidang -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="dashboard-card">
                <h2 class="card-title">Ringkasan Kinerja Seluruh Bidang</h2>

                <div class="bidang-grid-container">
                    <div class="bidang-grid">
                        @foreach ($bidangData as $bidang)
                            <div class="bidang-card">
                                <div class="bidang-header">
                                    <h3 class="bidang-title">{{ $bidang['nama'] }}</h3>
                                    <div class="badge {{ $bidang['nilai'] < 70 ? 'bg-danger' : ($bidang['nilai'] < 90 ? 'bg-warning' : 'bg-success') }}">
                                        {{ $bidang['nilai'] }}%
                                    </div>
                                </div>

                                <div class="bidang-progress">
                                    <div class="progress">
                                        <div class="progress-bar {{ $bidang['nilai'] < 70 ? 'progress-red' : ($bidang['nilai'] < 90 ? 'progress-yellow' : 'progress-green') }}"
                                            style="width: {{ $bidang['nilai'] }}%"></div>
                                    </div>
                                </div>

                                <div class="bidang-status">
                                    @if($bidang['nilai'] >= 90)
                                        <span class="status status-success">Sangat Baik</span>
                                    @elseif($bidang['nilai'] >= 80)
                                        <span class="status status-good">Baik</span>
                                    @elseif($bidang['nilai'] >= 70)
                                        <span class="status status-medium">Cukup</span>
                                    @else
                                        <span class="status status-poor">Kurang</span>
                                    @endif
                                </div>

                                <div class="bidang-actions">
                                    <a href="{{ route('dataKinerja.bidang', array_search($bidang['nama'], array_column(App\Models\Bidang::all()->toArray(), 'nama')) + 1) }}" class="btn btn-action btn-sm">
                                        <i class="fas fa-chart-bar"></i> Detail
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Bidang Chart
        const bidangChart = new Chart(document.getElementById('bidangChart'), {
            type: 'bar',
            data: {
                labels: [
                    @foreach($bidangData as $bidang)
                        '{{ $bidang['nama'] }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Pencapaian KPI (%)',
                    data: [
                        @foreach($bidangData as $bidang)
                            {{ $bidang['nilai'] }},
                        @endforeach
                    ],
                    backgroundColor: [
                        @foreach($bidangData as $bidang)
                            '{{ $bidang['nilai'] < 70 ? 'rgba(244, 67, 54, 0.7)' : ($bidang['nilai'] < 90 ? 'rgba(255, 193, 7, 0.7)' : 'rgba(76, 175, 80, 0.7)') }}',
                        @endforeach
                    ],
                    borderColor: [
                        @foreach($bidangData as $bidang)
                            '{{ $bidang['nilai'] < 70 ? 'rgba(244, 67, 54, 1)' : ($bidang['nilai'] < 90 ? 'rgba(255, 193, 7, 1)' : 'rgba(76, 175, 80, 1)') }}',
                        @endforeach
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                indexAxis: 'y',
                plugins: {
                    legend: {
                        position: 'top',
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.raw + '%';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
