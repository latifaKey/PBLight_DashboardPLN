@extends('layouts.app')

@section('title', 'Tambah Tahun Penilaian')
@section('page_title', 'TAMBAH TAHUN PENILAIAN')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Form Tambah Tahun Penilaian</h5>
            <a href="{{ route('tahunPenilaian.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('tahunPenilaian.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tahun" class="form-label">Tahun <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="tahun" name="tahun" min="2020" max="2100"
                                   value="{{ old('tahun', date('Y')) }}" required>
                            <small class="text-muted">Tahun berupa angka 4 digit, minimal 2020</small>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi (Opsional)</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="2">{{ old('deskripsi') }}</textarea>
                            <small class="text-muted">Berikan deskripsi singkat untuk tahun penilaian ini</small>
                        </div>

                        <div class="mb-3">
                            <label for="tipe_periode" class="form-label">Tipe Periode <span class="text-danger">*</span></label>
                            <select class="form-control" id="tipe_periode" name="tipe_periode" required>
                                <option value="tahunan" {{ old('tipe_periode') == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                                <option value="semesteran" {{ old('tipe_periode') == 'semesteran' ? 'selected' : '' }}>Semesteran</option>
                                <option value="triwulanan" {{ old('tipe_periode') == 'triwulanan' ? 'selected' : '' }}>Triwulanan</option>
                                <option value="bulanan" {{ old('tipe_periode') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                            </select>
                            <small class="text-muted">Pilih tipe periode untuk penilaian KPI</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai"
                                   value="{{ old('tanggal_mulai') }}">
                            <small class="text-muted">Jika dikosongkan, akan diisi otomatis berdasarkan tipe periode</small>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai"
                                   value="{{ old('tanggal_selesai') }}">
                            <small class="text-muted">Jika dikosongkan, akan diisi otomatis berdasarkan tipe periode</small>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_aktif" name="is_aktif" value="1"
                                       {{ old('is_aktif') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_aktif">
                                    Jadikan sebagai tahun aktif
                                </label>
                            </div>
                            <small class="text-muted">Jika dicentang, tahun lain yang aktif akan dinonaktifkan</small>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_locked" name="is_locked" value="1"
                                       {{ old('is_locked') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_locked">
                                    Kunci periode penilaian
                                </label>
                            </div>
                            <small class="text-muted">Jika dicentang, data periode ini tidak dapat diubah kecuali oleh Master Admin</small>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="{{ route('tahunPenilaian.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Logika untuk mengatur tanggal otomatis berdasarkan tipe periode yang dipilih
        const tipePeriodeSelect = document.getElementById('tipe_periode');
        const tahunInput = document.getElementById('tahun');
        const tanggalMulaiInput = document.getElementById('tanggal_mulai');
        const tanggalSelesaiInput = document.getElementById('tanggal_selesai');

        tipePeriodeSelect.addEventListener('change', function() {
            const tahun = tahunInput.value;
            if (!tahun || isNaN(tahun) || tahun < 2020) return;

            if (!tanggalMulaiInput.value && !tanggalSelesaiInput.value) {
                // Hanya isi otomatis jika kedua field tanggal kosong
                switch(this.value) {
                    case 'tahunan':
                        tanggalMulaiInput.value = `${tahun}-01-01`;
                        tanggalSelesaiInput.value = `${tahun}-12-31`;
                        break;
                    case 'semesteran':
                        tanggalMulaiInput.value = `${tahun}-01-01`;
                        tanggalSelesaiInput.value = `${tahun}-06-30`;
                        break;
                    case 'triwulanan':
                        tanggalMulaiInput.value = `${tahun}-01-01`;
                        tanggalSelesaiInput.value = `${tahun}-03-31`;
                        break;
                    case 'bulanan':
                        tanggalMulaiInput.value = `${tahun}-01-01`;
                        tanggalSelesaiInput.value = `${tahun}-01-31`;
                        break;
                }
            }
        });

        // Trigger change event untuk mengisi tanggal otomatis saat halaman dimuat
        tipePeriodeSelect.dispatchEvent(new Event('change'));
    });
</script>
@endsection
