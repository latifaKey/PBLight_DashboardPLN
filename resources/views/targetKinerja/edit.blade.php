@extends('layouts.app')

@section('title', 'Edit Target Kinerja')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Target Kinerja</h1>
        <a href="{{ route('targetKinerja.index', ['tahun_penilaian_id' => $tahunPenilaian->id]) }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    @include('components.alert')

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Form Edit Target Kinerja</h6>
                    @if($target->disetujui)
                        <span class="badge badge-success p-2">
                            <i class="fas fa-check-circle mr-1"></i> Target Sudah Disetujui
                        </span>
                    @else
                        <span class="badge badge-warning p-2">
                            <i class="fas fa-clock mr-1"></i> Menunggu Persetujuan
                        </span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="alert alert-info">
                            <h6 class="font-weight-bold">Informasi Indikator</h6>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <strong>Kode:</strong> {{ $indikator->kode }}
                                </div>
                                <div class="col-md-5">
                                    <strong>Nama:</strong> {{ $indikator->nama }}
                                </div>
                                <div class="col-md-4">
                                    <strong>Pilar:</strong> {{ $indikator->pilar->nama }}
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-3">
                                    <strong>Bobot:</strong> {{ $indikator->bobot }}%
                                </div>
                                <div class="col-md-5">
                                    <strong>Bidang:</strong> {{ $indikator->bidang->nama }}
                                </div>
                                <div class="col-md-4">
                                    <strong>Tahun Penilaian:</strong> {{ $tahunPenilaian->tahun }}
                                </div>
                            </div>
                            @if($indikator->deskripsi)
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <strong>Deskripsi:</strong> {{ $indikator->deskripsi }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <form action="{{ route('targetKinerja.update', $target->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="target_tahunan">Target Tahunan <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('target_tahunan') is-invalid @enderror"
                                   id="target_tahunan" name="target_tahunan" step="0.01"
                                   value="{{ old('target_tahunan', $target->target_tahunan) }}" required
                                   {{ $target->disetujui && !auth()->user()->isMasterAdmin() ? 'readonly' : '' }}>
                            @error('target_tahunan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Masukkan target tahunan untuk indikator ini.
                            </small>
                        </div>

                        <div class="form-group">
                            <label>Target Bulanan</label>
                            <div class="alert alert-warning">
                                <i class="fas fa-info-circle mr-1"></i>
                                Jika target bulanan tidak diisi, maka target tahunan akan dibagi rata ke 12 bulan.
                            </div>

                            <div class="row">
                                @php
                                    $bulanan = $target->target_bulanan ?? [];
                                    $defaultValue = round($target->target_tahunan/12, 2);
                                @endphp

                                @for($i = 1; $i <= 12; $i++)
                                    <div class="col-md-3 mb-2">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{{ \Carbon\Carbon::create(null, $i, 1)->locale('id')->monthName }}</span>
                                            </div>
                                            <input type="number" class="form-control"
                                                   name="target_bulanan[{{ $i-1 }}]"
                                                   step="0.01"
                                                   value="{{ old('target_bulanan.'.$i-1, $bulanan[$i-1] ?? $defaultValue) }}"
                                                   {{ $target->disetujui && !auth()->user()->isMasterAdmin() ? 'readonly' : '' }}>
                                        </div>
                                    </div>
                                @endfor
                            </div>

                            @error('target_bulanan')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            @error('target_bulanan.*')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror"
                                      id="keterangan" name="keterangan" rows="3"
                                      {{ $target->disetujui && !auth()->user()->isMasterAdmin() ? 'readonly' : '' }}>{{ old('keterangan', $target->keterangan) }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Tambahkan keterangan jika diperlukan.
                            </small>
                        </div>

                        @if(!$target->disetujui || auth()->user()->isMasterAdmin())
                            <div class="form-group text-center mt-4">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                                </button>

                                @if(auth()->user()->isMasterAdmin() && !$target->disetujui)
                                    <a href="{{ route('targetKinerja.approve', $target->id) }}"
                                       class="btn btn-success px-4 ml-2">
                                        <i class="fas fa-check-circle mr-1"></i> Setujui Target
                                    </a>
                                @endif

                                @if(auth()->user()->isMasterAdmin() && $target->disetujui)
                                    <a href="{{ route('targetKinerja.unapprove', $target->id) }}"
                                       class="btn btn-warning px-4 ml-2">
                                        <i class="fas fa-times-circle mr-1"></i> Batalkan Persetujuan
                                    </a>
                                @endif
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Target yang sudah disetujui tidak dapat diubah kecuali oleh Master Admin.
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Jika target tahunan berubah, update semua target bulanan
    document.getElementById('target_tahunan').addEventListener('change', function() {
        const targetTahunan = parseFloat(this.value) || 0;
        const targetBulanan = targetTahunan / 12;

        // Update semua input target bulanan
        const bulananInputs = document.querySelectorAll('input[name^="target_bulanan"]');
        bulananInputs.forEach(input => {
            input.value = targetBulanan.toFixed(2);
        });
    });
</script>
@endsection
