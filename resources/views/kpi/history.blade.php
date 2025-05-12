@extends('layouts.app')

@section('title', 'Riwayat KPI')
@section('page_title', 'RIWAYAT KPI')

@section('styles')
<style>
    .kpi-history-container {
        background: var(--pln-accent-bg);
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 5px 20px var(--pln-shadow);
        margin-bottom: 30px;
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
        height: 350px;
        margin-top: 30px;
        margin-bottom: 30px;
    }

    .pagination-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
    }

    .page-buttons {
        display: flex;
        gap: 10px;
    }

    .btn-page {
        background: var(--pln-surface);
        border: 1px solid var(--pln-border);
        border-radius: 8px;
        padding: 8px 15px;
        color: var(--pln-text);
        transition: all 0.2s ease;
    }

    .btn-page:hover {
        background: var(--pln-light-blue);
        color: white;
    }

    .btn-page.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .table-responsive {
        overflow-x: auto;
        max-height: 600px;
        position: relative;
    }

    .export-buttons {
        margin-bottom: 20px;
    }

    .export-buttons .btn {
        margin-right: 10px;
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
        border-radius: 12px;
        padding: 20px;
        border: 1px solid var(--pln-border);
        box-shadow: 0 4px 12px var(--pln-shadow);
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: transform 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-card-title {
        font-size: 1rem;
        margin-bottom: 10px;
        color: var(--pln-text-secondary);
        font-weight: 500;
    }

    .stat-card-value {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--pln-blue);
    }

    .stat-card-desc {
        font-size: 0.9rem;
        color: var(--pln-text-secondary);
        margin-top: auto;
    }

    .progress-bar {
        height: 8px;
        width: 100%;
        background: var(--pln-border);
        border-radius: 4px;
        margin-top: 10px;
        overflow: hidden;
    }

    .progress-bar-filled {
        height: 100%;
        border-radius: 4px;
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
        border-radius: 12px;
        padding: 20px;
        border: 1px solid var(--pln-border);
    }

    .stats-bidang-title {
        font-size: 1.1rem;
        margin-bottom: 15px;
        font-weight: 600;
        color: var(--pln-blue);
    }

    .bidang-stats-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .bidang-stat-item {
        padding: 15px;
        background: var(--pln-accent-bg);
        border-radius: 8px;
        border-left: 4px solid var(--pln-blue);
    }

    .bidang-name {
        font-weight: 600;
        margin-bottom: 8px;
    }

    .bidang-metrics {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        font-size: 0.9rem;
    }

    .bidang-metric {
        background: var(--pln-surface);
        padding: 5px 8px;
        border-radius: 4px;
        white-space: nowrap;
    }
</style>
@endsection

@section('content')
<div class="kpi-history-container">
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
                <i class="fas fa-chart-line me-2"></i>Rata-rata Pencapaian
            </div>
            <div class="stat-card-value">{{ $statistics['performa_ratarata'] }}%</div>
            <div class="stat-card-desc">
                Rata-rata pencapaian semua indikator KPI
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
                <i class="fas fa-tasks me-2"></i>Indikator Tercapai
            </div>
            <div class="stat-card-value">{{ $statistics['indikator_tercapai'] }}/{{ $statistics['total_indikator'] }}</div>
            <div class="stat-card-desc">
                Jumlah indikator yang mencapai target
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
                <i class="fas fa-check-circle me-2"></i>Terverifikasi
            </div>
            <div class="stat-card-value">{{ $statistics['persentase_diverifikasi'] }}%</div>
            <div class="stat-card-desc">
                Persentase data yang sudah diverifikasi
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
                <i class="fas fa-calendar-alt me-2"></i>Tahun Data
            </div>
            <div class="stat-card-value">{{ $tahun }}</div>
            <div class="stat-card-desc">
                Menampilkan data KPI untuk tahun {{ $tahun }}
            </div>
        </div>
    </div>

    @if(isset($statistics['bidang_stats']))
    <div class="stats-bidang-container">
        <div class="stats-bidang-title">
            <i class="fas fa-building me-2"></i>Performa per Bidang
        </div>
        <div class="bidang-stats-list">
            @foreach($statistics['bidang_stats'] as $bidangStat)
            <div class="bidang-stat-item">
                <div class="bidang-name">{{ $bidangStat['bidang'] }}</div>
                <div class="bidang-metrics">
                    <span class="bidang-metric">
                        <i class="fas fa-chart-line me-1"></i> {{ $bidangStat['performa_ratarata'] }}%
                    </span>
                    <span class="bidang-metric">
                        <i class="fas fa-tasks me-1"></i> {{ $bidangStat['indikator_tercapai'] }}/{{ $bidangStat['total_indikator'] }}
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
            <i class="fas fa-file-excel me-1"></i> Export Excel
        </button>
        <button class="btn btn-danger" onclick="exportToPdf()">
            <i class="fas fa-file-pdf me-1"></i> Export PDF
        </button>
    </div>

    @if(isset($pilars))
        <div class="kpi-chart-container">
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

        // Generate warna berbeda untuk setiap pilar
        const colors = [
            'rgba(0, 123, 255, 0.7)',
            'rgba(40, 167, 69, 0.7)',
            'rgba(255, 193, 7, 0.7)',
            'rgba(220, 53, 69, 0.7)',
            'rgba(23, 162, 184, 0.7)',
            'rgba(153, 102, 255, 0.7)'
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
                    pilarCounts[index] > 0 ? value / pilarCounts[index] : 0
                );

                datasets.push({
                    label: '{{ $pilar->nama }}',
                    data: avgData,
                    borderColor: colors[{{ $index % count($pilars) }}],
                    backgroundColor: colors[{{ $index % count($pilars) }}],
                    tension: 0.3,
                    fill: false
                });
            @endforeach
        @endif

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Tren Pencapaian KPI per Pilar ({{ request('tahun', date('Y')) }})',
                        font: {
                            size: 16
                        }
                    },
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y.toFixed(2) + '%';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        title: {
                            display: true,
                            text: 'Persentase Pencapaian (%)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Bulan'
                        }
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
