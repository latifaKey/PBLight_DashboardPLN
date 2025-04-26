<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard untuk master_admin (asisten_manager)
     */
    public function master()
    {
        // Pastikan hanya asisten_manager yang bisa mengakses
        if (auth()->user()->role !== 'asisten_manager') {
            return redirect()->route('dashboard');
        }

        return view('dashboard.master');
    }

    /**
     * Dashboard untuk admin berdasarkan peran mereka
     */
    public function admin()
    {
        $user = auth()->user();

        // Validasi role admin yang memiliki akses dashboard admin
        $allowedRoles = [
            'pic_keuangan',
            'pic_risiko_manajemen',
            'pic_skreperusahaan',
            'pic_perencanaan_operasi',
            'pic_pengembangan_bisnis',
            'pic_human_capital',
            'pic_k3l',
            'pic_perencanaan_korporat'
        ];

        // Jika role user tidak ada dalam daftar allowedRoles, redirect ke dashboard utama
        if (!in_array($user->role, $allowedRoles)) {
            return redirect()->route('dashboard');
        }

        // Jika role valid, tampilkan dashboard admin
        return view('dashboard.admin');
    }

    /**
     * Dashboard untuk user (karyawan)
     */
    public function user()
    {
        // Pastikan hanya karyawan yang bisa mengakses
        if (auth()->user()->role !== 'karyawan') {
            return redirect()->route('dashboard');
        }

        return view('dashboard.user');
    }

    /**
     * Halaman utama dashboard yang mengarahkan sesuai role
     */
    public function index()
    {
        // Mendapatkan role user
        $role = auth()->user()->role;

        // Redirect berdasarkan role
        switch ($role) {
            case 'asisten_manager':
                return redirect()->route('dashboard.master');

            case 'pic_keuangan':
            case 'pic_risiko_manajemen':
            case 'pic_skreperusahaan':
            case 'pic_perencanaan_operasi':
            case 'pic_pengembangan_bisnis':
            case 'pic_human_capital':
            case 'pic_k3l':
            case 'pic_perencanaan_korporat':
                return redirect()->route('dashboard.admin');

            case 'karyawan':
                return redirect()->route('dashboard.user');

            default:
                return redirect()->route('login')->with('error', 'Role tidak dikenali.');
        }
    }
}
