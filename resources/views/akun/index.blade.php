@extends('layouts.app')
{{-- @extends('layouts.master') --}}

@section('title', 'Kelola Akun')
@section('page_title', 'Daftar Akun')

@section('content')

<style>
    .akun-container {
        background: #fff;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        width: 90%;
        max-width: 1000px; /* Tambahkan ini */
        margin: 40px auto; /* Tengah + atas-bawah kasih jarak */
    }

    .akun-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .akun-title {
        margin: 0;
    }

    .btn-tambah-akun {
        background: #00566b;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        padding: 10px 15px;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .table-responsive {
        overflow-x: auto;

    }

    .akun-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        table-layout: auto;

    }

    .akun-table thead tr {
        background: #00566b;
        color: white;
        text-align: left;
    }

    .akun-table th,
    .akun-table td {
        padding: 12px 10px;
        border-bottom: 1px solid #ccc;
    }

    .btn-detail {
        background: #17a2b8;
        color: white;
        padding: 6px 12px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 13px;
    }

    .btn-edit {
        background: #ffc107;
        color: black;
        padding: 6px 12px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 13px;
    }

    .btn-hapus {
        background: #dc3545;
        color: white;
        padding: 6px 12px;
        border: none;
        border-radius: 5px;
        font-size: 13px;
        cursor: pointer;
    }

    .form-inline {
        display: inline;
    }

    .text-center {
        text-align: center;
    }

    .text-muted {
        color: #888;
    }
</style>

<div class="akun-container">
    <div class="akun-header">
        <h2 class="akun-title">Daftar Akun</h2>
        <a href="{{ route('akun.create') }}" class="btn-tambah-akun">Tambah Akun</a>
    </div>

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="akun-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ ucwords(str_replace('_', ' ', $user->role)) }}</td>
                        <td>
                            <a href="{{ route('akun.show', $user->id) }}" class="btn-detail">Detail</a>
                            <a href="{{ route('akun.edit', $user->id) }}" class="btn-edit">Edit</a>
                            <form action="{{ route('akun.destroy', $user->id) }}" method="POST" class="form-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin ingin menghapus akun ini?')" class="btn-hapus">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach

                @if ($users->isEmpty())
                    <tr>
                        <td colspan="4" class="text-center text-muted">Belum ada data akun.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

@endsection
