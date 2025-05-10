@extends('layouts.app')

@section('title', 'Manajemen Tahun Penilaian')
@section('page_title', 'MANAJEMEN TAHUN PENILAIAN')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Daftar Tahun Penilaian</h5>
            <a href="{{ route('tahunPenilaian.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Tahun Penilaian
            </a>
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

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th width="5%">No</th>
                            <th width="10%">Tahun</th>
                            <th width="15%">Tipe Periode</th>
                            <th width="15%">Periode</th>
                            <th width="20%">Deskripsi</th>
                            <th width="10%">Status</th>
                            <th width="25%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tahunPenilaians as $index => $tahun)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $tahun->tahun }}</td>
                                <td>{{ $tahun->getTipePeriodeLabel() }}</td>
                                <td>
                                    @if($tahun->tanggal_mulai && $tahun->tanggal_selesai)
                                        {{ $tahun->tanggal_mulai->format('d/m/Y') }} - {{ $tahun->tanggal_selesai->format('d/m/Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $tahun->deskripsi ?? '-' }}</td>
                                <td>
                                    @if($tahun->is_aktif)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                    @endif

                                    @if($tahun->is_locked)
                                        <span class="badge bg-danger">Terkunci</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @if(!$tahun->is_aktif)
                                            <a href="{{ route('tahunPenilaian.activate', $tahun->id) }}" class="btn btn-sm btn-success"
                                               title="Aktifkan" onclick="return confirm('Anda yakin ingin mengaktifkan tahun penilaian ini?')">
                                                <i class="fas fa-check"></i> Aktifkan
                                            </a>
                                        @endif

                                        @if(!$tahun->is_locked)
                                            <a href="{{ route('tahunPenilaian.lock', $tahun->id) }}" class="btn btn-sm btn-warning"
                                               title="Kunci" onclick="return confirm('Anda yakin ingin mengunci tahun penilaian ini? Data yang terkunci tidak dapat diubah kecuali oleh Master Admin.')">
                                                <i class="fas fa-lock"></i> Kunci
                                            </a>
                                        @else
                                            <a href="{{ route('tahunPenilaian.unlock', $tahun->id) }}" class="btn btn-sm btn-info"
                                               title="Buka Kunci" onclick="return confirm('Anda yakin ingin membuka kunci tahun penilaian ini?')">
                                                <i class="fas fa-unlock"></i> Buka Kunci
                                            </a>
                                        @endif

                                        <a href="{{ route('tahunPenilaian.edit', $tahun->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>

                                        @if(!$tahun->is_aktif && !$tahun->is_locked)
                                            <form action="{{ route('tahunPenilaian.destroy', $tahun->id) }}" method="POST" style="display: inline;"
                                                  onsubmit="return confirm('Anda yakin ingin menghapus tahun penilaian ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada data tahun penilaian</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
