@extends('layouts.app')

@section('title', 'Detail Verifikasi KPI')
@section('page_title', 'DETAIL VERIFIKASI KPI')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Detail KPI</h5>
            <a href="{{ route('verifikasi.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(isset($isPeriodeLocked) && $isPeriodeLocked)
                <div class="alert alert-warning alert-dismissible fade show">
                    <strong>Peringatan!</strong> Periode penilaian tahun {{ $nilaiKPI->tahun }} sedang terkunci. Anda tidak dapat melakukan verifikasi pada periode ini.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">Informasi Indikator</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Kode KPI</th>
                                    <td width="70%">{{ $nilaiKPI->indikator->kode }}</td>
                                </tr>
                                <tr>
                                    <th>Nama KPI</th>
                                    <td>{{ $nilaiKPI->indikator->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Bidang</th>
                                    <td>{{ $nilaiKPI->indikator->bidang->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Pilar</th>
                                    <td>{{ $nilaiKPI->indikator->pilar->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Satuan</th>
                                    <td>{{ $nilaiKPI->indikator->satuan }}</td>
                                </tr>
                                <tr>
                                    <th>Bobot</th>
                                    <td>{{ $nilaiKPI->indikator->bobot }}%</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0">Informasi Nilai</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Periode</th>
                                    <td width="70%">{{ $nilaiKPI->tahun }} - {{ date('F', mktime(0, 0, 0, $nilaiKPI->bulan, 1)) }}</td>
                                </tr>
                                <tr>
                                    <th>Nilai</th>
                                    <td>{{ $nilaiKPI->nilai }} {{ $nilaiKPI->indikator->satuan }}</td>
                                </tr>
                                <tr>
                                    <th>Target</th>
                                    <td>{{ $nilaiKPI->target ?? '-' }} {{ $nilaiKPI->indikator->satuan }}</td>
                                </tr>
                                <tr>
                                    <th>Persentase</th>
                                    <td>
                                        <div class="progress mb-2">
                                            @php
                                                $progressClass = 'bg-danger';
                                                if ($nilaiKPI->persentase >= 70) {
                                                    $progressClass = 'bg-success';
                                                } elseif ($nilaiKPI->persentase >= 50) {
                                                    $progressClass = 'bg-warning';
                                                }
                                            @endphp
                                            <div class="progress-bar {{ $progressClass }}" role="progressbar" style="width: {{ $nilaiKPI->persentase }}%" aria-valuenow="{{ $nilaiKPI->persentase }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        {{ number_format($nilaiKPI->persentase, 2) }}%
                                    </td>
                                </tr>
                                <tr>
                                    <th>Diinput Oleh</th>
                                    <td>{{ $nilaiKPI->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Input</th>
                                    <td>{{ $nilaiKPI->created_at->format('d-m-Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">Informasi Pendukung</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <div class="border p-3 rounded">
                            {!! nl2br(e($nilaiKPI->keterangan)) ?: '<em>Tidak ada keterangan</em>' !!}
                        </div>
                    </div>

                    @if($nilaiKPI->bukti_url)
                        <div class="mb-3">
                            <label class="form-label">Bukti Pendukung</label>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-file-alt me-2"></i>
                                <a href="{{ asset('storage/' . $nilaiKPI->bukti_url) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download"></i> Lihat/Unduh Bukti
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="d-flex justify-content-end">
                @if(!isset($isPeriodeLocked) || !$isPeriodeLocked)
                <form action="{{ route('verifikasi.update', $nilaiKPI->id) }}" method="POST" class="me-2">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success" onclick="return confirm('Anda yakin ingin memverifikasi nilai KPI ini?')">
                        <i class="fas fa-check"></i> Verifikasi KPI
                    </button>
                </form>

                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalTolak">
                    <i class="fas fa-times"></i> Tolak KPI
                </button>
                @else
                <div class="alert alert-warning">
                    <i class="fas fa-lock"></i> Periode penilaian terkunci. Tidak dapat melakukan verifikasi atau penolakan.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Tolak -->
<div class="modal fade" id="modalTolak" tabindex="-1" aria-labelledby="modalTolakLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('verifikasi.destroy', $nilaiKPI->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTolakLabel">Tolak Nilai KPI</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="alasan_penolakan" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="alasan_penolakan" name="alasan_penolakan" rows="4" required></textarea>
                        <small class="text-muted">Berikan alasan yang jelas mengapa nilai KPI ini ditolak</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak KPI</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
