@extends('layouts.app')

@section('title', 'Verifikasi KPI')
@section('page_title', 'VERIFIKASI KPI')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Daftar KPI yang Menunggu Verifikasi</h5>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show">
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(isset($isPeriodeLocked) && $isPeriodeLocked)
                <div class="alert alert-warning alert-dismissible fade show">
                    <strong>Peringatan!</strong> Periode penilaian tahun {{ $tahun }} sedang terkunci. Anda tidak dapat melakukan verifikasi pada periode ini.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Form Filter -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Filter Data</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('verifikasi.index') }}" method="GET" class="row align-items-end">
                        <div class="col-md-3 mb-3">
                            <label for="tahun" class="form-label">Tahun</label>
                            <select class="form-select" id="tahun" name="tahun">
                                @php
                                    $currentYear = date('Y');
                                    $startYear = 2020;
                                @endphp
                                @for($y = $currentYear; $y >= $startYear; $y--)
                                    <option value="{{ $y }}" {{ $y == $tahun ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="bulan" class="form-label">Bulan</label>
                            <select class="form-select" id="bulan" name="bulan">
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ sprintf('%02d', $m) }}" {{ sprintf('%02d', $m) == $bulan ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="bidang_id" class="form-label">Bidang</label>
                            <select class="form-select" id="bidang_id" name="bidang_id">
                                <option value="">-- Semua Bidang --</option>
                                @foreach($bidangs as $bidang)
                                    <option value="{{ $bidang->id }}" {{ $bidangId == $bidang->id ? 'selected' : '' }}>{{ $bidang->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('verifikasi.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-sync"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            @if($nilaiKPIs->count() > 0)
                <form action="{{ route('verifikasi.massal') }}" method="POST" id="form-verifikasi-massal">
                    @csrf

                    <div class="mb-3">
                        <button type="submit" class="btn btn-success" {{ isset($isPeriodeLocked) && $isPeriodeLocked ? 'disabled' : '' }} id="btn-verifikasi-massal" disabled>
                            <i class="fas fa-check-double"></i> Verifikasi Terpilih
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th width="5%">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="checkAll" {{ isset($isPeriodeLocked) && $isPeriodeLocked ? 'disabled' : '' }}>
                                        </div>
                                    </th>
                                    <th width="10%">KPI</th>
                                    <th width="25%">Indikator</th>
                                    <th width="15%">Bidang</th>
                                    <th width="10%">Periode</th>
                                    <th width="10%">Nilai</th>
                                    <th width="10%">Uploaded By</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($nilaiKPIs as $kpi)
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input check-item" type="checkbox" name="nilai_ids[]"
                                                       value="{{ $kpi->id }}" {{ isset($isPeriodeLocked) && $isPeriodeLocked ? 'disabled' : '' }}>
                                            </div>
                                        </td>
                                        <td>{{ $kpi->indikator->kode }}</td>
                                        <td>{{ $kpi->indikator->nama }}</td>
                                        <td>{{ $kpi->indikator->bidang->nama }}</td>
                                        <td>{{ $kpi->tahun }}-{{ $kpi->bulan }}</td>
                                        <td>{{ $kpi->nilai }}</td>
                                        <td>{{ $kpi->user->name }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('verifikasi.show', $kpi->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                                @if(!isset($isPeriodeLocked) || !$isPeriodeLocked)
                                                <form action="{{ route('verifikasi.update', $kpi->id) }}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Yakin verifikasi KPI ini?')">
                                                        <i class="fas fa-check"></i> Verifikasi
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>

                <div class="mt-4">
                    {{ $nilaiKPIs->appends(['tahun' => $tahun, 'bulan' => $bulan, 'bidang_id' => $bidangId])->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Tidak ada data KPI yang menunggu verifikasi untuk periode ini.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi untuk cek apakah ada checkbox yang dicentang
        function checkSelected() {
            const checkboxes = document.querySelectorAll('.check-item:checked');
            document.getElementById('btn-verifikasi-massal').disabled = checkboxes.length === 0;
        }

        // Check all / uncheck all
        const checkAllBox = document.getElementById('checkAll');
        if (checkAllBox) {
            checkAllBox.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.check-item');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = checkAllBox.checked;
                });
                checkSelected();
            });
        }

        // Individual check
        const checkItems = document.querySelectorAll('.check-item');
        checkItems.forEach(item => {
            item.addEventListener('change', function() {
                checkSelected();

                // Update checkAll status
                const allChecked = document.querySelectorAll('.check-item:checked').length === checkItems.length;
                if (checkAllBox) {
                    checkAllBox.checked = allChecked;
                }
            });
        });

        // Form submit confirm
        const form = document.getElementById('form-verifikasi-massal');
        if (form) {
            form.addEventListener('submit', function(event) {
                const checkboxes = document.querySelectorAll('.check-item:checked');
                if (checkboxes.length === 0) {
                    event.preventDefault();
                    alert('Silakan pilih setidaknya satu KPI untuk diverifikasi.');
                    return false;
                }

                return confirm('Anda yakin ingin memverifikasi ' + checkboxes.length + ' KPI yang dipilih?');
            });
        }
    });
</script>
@endsection
