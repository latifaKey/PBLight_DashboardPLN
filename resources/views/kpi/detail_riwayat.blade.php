@extends('layouts.app')

@section('title', 'Detail Riwayat KPI')
@section('page_title', 'DETAIL RIWAYAT KPI')

@section('styles')
<style>
    .riwayat-detail-container {
        background: var(--pln-accent-bg);
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 5px 20px var(--pln-shadow);
        margin-bottom: 30px;
    }

    .indikator-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        background: var(--pln-surface);
        padding: 15px 20px;
        border-radius: 12px;
        border-left: 5px solid var(--pln-blue);
    }

    .indikator-title {
        margin: 0;
        font-size: 1.5rem;
        color: var(--pln-blue);
    }

    .indikator-subtitle {
        font-size: 1rem;
        color: var(--pln-text-secondary);
        margin-top: 5px;
    }

    .indikator-code {
        background: var(--pln-light-blue);
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-weight: 500;
        margin-right: 10px;
    }

    .riwayat-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 20px;
    }

    .riwayat-table th {
        background: linear-gradient(90deg, var(--pln-blue), var(--pln-light-blue));
        color: white;
        padding: 12px 15px;
        text-align: center;
        font-weight: 600;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .riwayat-table th:first-child {
        border-top-left-radius: 8px;
    }

    .riwayat-table th:last-child {
        border-top-right-radius: 8px;
    }

    .riwayat-table td {
        padding: 12px 15px;
        border-bottom: 1px solid var(--pln-border);
        text-align: center;
    }

    .riwayat-table tr:last-child td {
        border-bottom: none;
    }

    .riwayat-table tr:hover td {
        background-color: rgba(0, 156, 222, 0.05);
    }

    .riwayat-table .text-success {
        color: #28a745 !important;
        font-weight: 600;
    }

    .riwayat-table .text-warning {
        color: #ffc107 !important;
        font-weight: 600;
    }

    .riwayat-table .text-danger {
        color: #dc3545 !important;
        font-weight: 600;
    }

    .riwayat-sections {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-bottom: 30px;
    }

    @media (max-width: 992px) {
        .riwayat-sections {
            grid-template-columns: 1fr;
        }
    }

    .riwayat-section {
        background: var(--pln-surface);
        border-radius: 12px;
        padding: 20px;
        border: 1px solid var(--pln-border);
    }

    .riwayat-section-title {
        font-size: 1.2rem;
        color: var(--pln-blue);
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--pln-border);
        display: flex;
        align-items: center;
    }

    .riwayat-section-title i {
        margin-right: 10px;
    }

    .finalisasi-form {
        background: var(--pln-surface);
        border-radius: 12px;
        padding: 20px;
        margin-top: 20px;
        border: 1px solid var(--pln-border);
    }

    .finalisasi-form-title {
        font-size: 1.2rem;
        color: var(--pln-blue);
        margin-bottom: 15px;
    }

    .bulan-name {
        font-weight: 600;
    }

    .nilai-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: 600;
    }

    .back-button {
        margin-bottom: 20px;
    }

    .nilai-chart-container {
        height: 300px;
        margin-top: 30px;
    }

    .finalisasi-button {
        margin-top: 5px;
        display: inline-block;
    }

    .status-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .status-finalisasi {
        background: rgba(40, 167, 69, 0.1);
        color: #28a745;
        border: 1px solid rgba(40, 167, 69, 0.2);
    }

    .status-pending {
        background: rgba(255, 193, 7, 0.1);
        color: #ffc107;
        border: 1px solid rgba(255, 193, 7, 0.2);
    }

    .status-need-verification {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.2);
    }
</style>
@endsection

@section('content')
<div class="back-button">
    <a href="{{ route('kpi.history', ['tahun' => $tahun]) }}" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left me-2"></i> Kembali ke Riwayat KPI
    </a>
</div>

<div class="riwayat-detail-container">
    <div class="indikator-header">
        <div>
            <h2 class="indikator-title">
                <span class="indikator-code">{{ $indikator->kode }}</span>
                {{ $indikator->nama }}
            </h2>
            <div class="indikator-subtitle">
                <i class="fas fa-building me-2"></i> {{ $indikator->bidang->nama ?? 'Tidak ada bidang' }}
                <span class="ms-3"><i class="fas fa-calendar me-2"></i> Tahun {{ $tahun }}</span>
            </div>
        </div>
        <div>
            <button type="button" class="btn btn-success" onclick="exportData('excel')">
                <i class="fas fa-file-excel me-1"></i> Export Excel
            </button>
            <button type="button" class="btn btn-danger" onclick="exportData('pdf')">
                <i class="fas fa-file-pdf me-1"></i> Export PDF
            </button>
        </div>
    </div>

    <div class="riwayat-sections">
        <div class="riwayat-section">
            <div class="riwayat-section-title">
                <i class="fas fa-chart-line"></i> Grafik Pencapaian {{ $tahun }}
            </div>
            <div class="nilai-chart-container">
                <canvas id="nilaiChart"></canvas>
            </div>
        </div>

        <div class="riwayat-section">
            <div class="riwayat-section-title">
                <i class="fas fa-info-circle"></i> Informasi Indikator
            </div>
            <table class="table">
                <tbody>
                    <tr>
                        <td width="30%"><strong>Kode</strong></td>
                        <td>{{ $indikator->kode }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nama</strong></td>
                        <td>{{ $indikator->nama }}</td>
                    </tr>
                    <tr>
                        <td><strong>Deskripsi</strong></td>
                        <td>{{ $indikator->deskripsi ?? 'Tidak ada deskripsi' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Satuan</strong></td>
                        <td>{{ $indikator->satuan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Bobot</strong></td>
                        <td>{{ $indikator->bobot ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tipe Target</strong></td>
                        <td>{{ $indikator->tipe_target ?? 'Standar' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td>
                            @if($indikator->aktif)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Tidak Aktif</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="table-responsive">
        <table class="riwayat-table">
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th>Target</th>
                    <th>Realisasi</th>
                    <th>Pencapaian (%)</th>
                    <th>Status</th>
                    <th>Finalisasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $namaBulan = [
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                    ];
                @endphp

                @foreach($nilaiKPIs as $nilai)
                    @php
                        $persentaseClass = '';
                        if($nilai->persentase >= 80) $persentaseClass = 'text-success';
                        elseif($nilai->persentase >= 60) $persentaseClass = 'text-warning';
                        elseif($nilai->persentase > 0) $persentaseClass = 'text-danger';

                        $riwayat = $nilaiRiwayat->where('bulan', $nilai->bulan)->first();
                        $statusLabel = '';
                        $statusClass = '';

                        if ($riwayat) {
                            $statusLabel = 'Finalisasi';
                            $statusClass = 'status-finalisasi';
                        } elseif ($nilai->diverifikasi) {
                            $statusLabel = 'Menunggu Finalisasi';
                            $statusClass = 'status-pending';
                        } else {
                            $statusLabel = 'Belum Diverifikasi';
                            $statusClass = 'status-need-verification';
                        }
                    @endphp

                    <tr>
                        <td class="bulan-name">{{ $namaBulan[$nilai->bulan] }}</td>
                        <td>{{ number_format($nilai->target, 2) }} {{ $indikator->satuan }}</td>
                        <td>{{ number_format($nilai->nilai, 2) }} {{ $indikator->satuan }}</td>
                        <td class="{{ $persentaseClass }}">
                            <span class="nilai-badge" style="background: rgba({{ $persentaseClass == 'text-success' ? '40, 167, 69, 0.1' : ($persentaseClass == 'text-warning' ? '255, 193, 7, 0.1' : '220, 53, 69, 0.1') }});">
                                {{ number_format($nilai->persentase, 2) }}%
                            </span>
                        </td>
                        <td>
                            <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                        </td>
                        <td>
                            @if($riwayat)
                                <div>{{ $riwayat->finalisasi_tanggal->format('d M Y') }}</div>
                                <small>oleh {{ $riwayat->finalisasiUser->name ?? 'Unknown' }}</small>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if(!$riwayat && $nilai->diverifikasi)
                                <button type="button" class="btn btn-sm btn-primary"
                                        onclick="showFinalisasiModal({{ $nilai->id }}, '{{ $namaBulan[$nilai->bulan] }}', {{ $nilai->persentase }})">
                                    <i class="fas fa-check-circle me-1"></i> Finalisasi
                                </button>
                            @elseif($riwayat)
                                <button type="button" class="btn btn-sm btn-info"
                                        onclick="showRiwayatDetail({{ $riwayat->id }})">
                                    <i class="fas fa-eye me-1"></i> Detail
                                </button>
                            @else
                                <button type="button" class="btn btn-sm btn-secondary" disabled>
                                    <i class="fas fa-clock me-1"></i> Tunggu Verifikasi
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Finalisasi -->
<div class="modal fade" id="finalisasiModal" tabindex="-1" aria-labelledby="finalisasiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="finalisasiModalLabel">Finalisasi Nilai KPI</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="finalisasiForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <span class="finalisasi-info mb-3 d-block">
                            Anda akan memfinalisasi nilai KPI <strong id="finalisasiBulan"></strong> dengan pencapaian <strong id="finalisasiPersentase"></strong>%.
                            <br>Data yang sudah difinalisasi akan disimpan dalam riwayat.
                        </span>

                        <label for="keterangan" class="form-label">Keterangan (opsional)</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Tambahkan keterangan jika diperlukan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Finalisasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail Riwayat -->
<div class="modal fade" id="detailRiwayatModal" tabindex="-1" aria-labelledby="detailRiwayatModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailRiwayatModalLabel">Detail Riwayat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailRiwayatContent">
                <!-- Akan diisi dengan AJAX -->
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        initializeChart();
    });

    function initializeChart() {
        const ctx = document.getElementById('nilaiChart').getContext('2d');

        // Data bulan
        const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        // Ambil data dari nilaiKPIs untuk performa chart
        const nilaiData = [];
        const targetData = [];

        @foreach($nilaiKPIs->sortBy('bulan') as $nilai)
            nilaiData.push({{ $nilai->persentase }});
            targetData.push(100); // Target ideal 100%
        @endforeach

        // Data riwayat yang sudah difinalisasi
        const riwayatData = Array(12).fill(null);

        @foreach($nilaiRiwayat as $riwayat)
            riwayatData[{{ $riwayat->bulan - 1 }}] = {{ $riwayat->persentase }};
        @endforeach

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Persentase Pencapaian',
                        data: nilaiData,
                        borderColor: 'rgba(0, 123, 255, 0.7)',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Finalisasi',
                        data: riwayatData,
                        borderColor: 'rgba(40, 167, 69, 0.7)',
                        backgroundColor: 'rgba(40, 167, 69, 0.7)',
                        borderWidth: 0,
                        pointRadius: 6,
                        pointStyle: 'rectRot',
                        pointBorderWidth: 0,
                        type: 'scatter'
                    },
                    {
                        label: 'Target Ideal',
                        data: targetData,
                        borderColor: 'rgba(220, 53, 69, 0.5)',
                        borderWidth: 1,
                        borderDash: [5, 5],
                        fill: false,
                        pointRadius: 0
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Pencapaian KPI {{ $indikator->nama }} ({{ $tahun }})',
                        font: {
                            size: 14
                        }
                    },
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                if (context.dataset.label === 'Finalisasi' && context.parsed.y !== null) {
                                    return 'Finalisasi: ' + context.parsed.y.toFixed(2) + '%';
                                }
                                return context.dataset.label + ': ' + context.parsed.y.toFixed(2) + '%';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 120,
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

    function showFinalisasiModal(nilaiId, bulan, persentase) {
        document.getElementById('finalisasiBulan').textContent = bulan;
        document.getElementById('finalisasiPersentase').textContent = persentase.toFixed(2);
        document.getElementById('finalisasiForm').action = "{{ route('kpi.finalisasi', '') }}/" + nilaiId;

        const modal = new bootstrap.Modal(document.getElementById('finalisasiModal'));
        modal.show();
    }

    function showRiwayatDetail(riwayatId) {
        const modal = new bootstrap.Modal(document.getElementById('detailRiwayatModal'));
        modal.show();

        // Implementasi AJAX untuk mengambil detail riwayat
        // Ini bisa diimplementasikan nanti
        document.getElementById('detailRiwayatContent').innerHTML = `
            <div class="alert alert-info">
                Detail riwayat akan ditampilkan di sini.
                <br>ID Riwayat: ${riwayatId}
            </div>
        `;
    }

    function exportData(type) {
        const tahun = {{ $tahun }};
        const indikatorId = {{ $indikator->id }};

        if (type === 'excel') {
            window.location.href = `{{ route('kpi.export.excel') }}?tahun=${tahun}&indikator_id=${indikatorId}`;
        } else if (type === 'pdf') {
            window.location.href = `{{ route('kpi.export.pdf') }}?tahun=${tahun}&indikator_id=${indikatorId}`;
        }
    }
</script>
@endsection
