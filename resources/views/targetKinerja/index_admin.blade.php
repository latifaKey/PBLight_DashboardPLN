@extends('layouts.app')

@section('title', 'Data Target Kinerja')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Target Kinerja {{ $bidang->nama }}</h1>
    </div>

    @include('components.alert')

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Target KPI Tahun {{ $tahunPenilaian->tahun }}</h6>
            <div>
                <div class="dropdown d-inline-block mr-2">
                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="tahunDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Tahun Penilaian
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="tahunDropdown">
                        @foreach(\App\Models\TahunPenilaian::orderBy('tahun', 'desc')->get() as $tp)
                            <a class="dropdown-item {{ $tp->id == $tahunPenilaian->id ? 'active' : '' }}"
                               href="{{ route('targetKinerja.index', ['tahun_penilaian_id' => $tp->id]) }}">
                                {{ $tp->tahun }} {{ $tp->is_active ? '(Aktif)' : '' }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($tahunPenilaian->is_locked)
                <div class="alert alert-warning">
                    <i class="fas fa-lock mr-2"></i> Tahun penilaian ini telah dikunci. Data target tidak dapat diubah.
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="10%">Kode</th>
                            <th width="30%">Indikator</th>
                            <th width="10%">Bobot</th>
                            <th width="15%">Target Tahunan</th>
                            <th width="10%">Status</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($indikators as $index => $indikator)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $indikator->kode }}</td>
                                <td>
                                    <strong>{{ $indikator->nama }}</strong>
                                    @if($indikator->deskripsi)
                                        <div class="small text-muted">{{ $indikator->deskripsi }}</div>
                                    @endif
                                    <div class="badge badge-info">{{ $indikator->pilar->nama }}</div>
                                </td>
                                <td>{{ $indikator->bobot }}%</td>
                                <td>
                                    @if(isset($indikator->target_data))
                                        {{ number_format($indikator->target_data->target_tahunan, 2) }}
                                    @else
                                        <span class="text-danger">Belum Diatur</span>
                                    @endif
                                </td>
                                <td>
                                    @if(isset($indikator->target_data))
                                        @if($indikator->target_data->disetujui)
                                            <span class="badge badge-success">Disetujui</span>
                                        @else
                                            <span class="badge badge-warning">Menunggu Persetujuan</span>
                                        @endif
                                    @else
                                        <span class="badge badge-danger">Belum Diatur</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!$tahunPenilaian->is_locked)
                                        @if(isset($indikator->target_data))
                                            @if(!$indikator->target_data->disetujui)
                                                <a href="{{ route('targetKinerja.edit', $indikator->target_data->id) }}"
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                            @else
                                                <button class="btn btn-sm btn-secondary" disabled>
                                                    <i class="fas fa-check"></i> Sudah Disetujui
                                                </button>
                                            @endif
                                        @else
                                            <a href="{{ route('targetKinerja.create', ['indikator_id' => $indikator->id, 'tahun_penilaian_id' => $tahunPenilaian->id]) }}"
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-plus"></i> Tambah Target
                                            </a>
                                        @endif
                                    @else
                                        <button class="btn btn-sm btn-secondary" disabled>
                                            <i class="fas fa-lock"></i> Terkunci
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada indikator aktif untuk bidang ini</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
