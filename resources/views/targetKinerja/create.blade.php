@extends('layouts.app')

@section('title', 'Tambah Target Kinerja')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Target Kinerja</h1>
        <a href="{{ route('targetKinerja.index', ['tahun_penilaian_id' => $tahunPenilaian->id]) }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    @include('components.alert')

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Form Target Kinerja</h6>
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

                    <form action="{{ route('targetKinerja.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="indikator_id" value="{{ $indikator->id }}">
                        <input type="hidden" name="tahun_penilaian_id" value="{{ $tahunPenilaian->id }}">

                        <div class="form-group">
                            <label for="target_tahunan">Target Tahunan <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('target_tahunan') is-invalid @enderror"
                                   id="target_tahunan" name="target_tahunan" step="0.01"
                                   value="{{ old('target_tahunan', $indikator->target) }}" required>
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
                                @for($i = 1; $i <= 12; $i++)
                                    <div class="col-md-3 mb-2">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{{ \Carbon\Carbon::create(null, $i, 1)->locale('id')->monthName }}</span>
                                            </div>
                                            <input type="number" class="form-control"
                                                   name="target_bulanan[{{ $i-1 }}]"
                                                   step="0.01"
                                                   value="{{ old('target_bulanan.'.$i-1, round($indikator->target/12, 2)) }}">
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
                                      id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Tambahkan keterangan jika diperlukan.
                            </small>
                        </div>

                        <div class="form-group text-center mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save mr-1"></i> Simpan Target
                            </button>
                        </div>
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
