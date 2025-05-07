<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Dashboard untuk master_admin (asisten_manager)
     */
    public function master()
    {
        // Pastikan hanya asisten_manager yang bisa mengakses
        if (Auth::user()->role !== 'asisten_manager') {
            return redirect()->route('dashboard');
        }

        // Data untuk dashboard performa organisasi
        $data = [
            'nko' => 29.416, // Nilai default NKO
            'pilar' => [
                [
                    'nama' => 'Nilai Ekonomi dan Sosial',
                    'nilai' => 23,
                    'indikator' => [
                        ['nama' => 'EBITDA', 'nilai' => 5.67],
                        ['nama' => 'Operating Ratio', 'nilai' => 42.08],
                        ['nama' => 'ROIC', 'nilai' => 7.13],
                        ['nama' => 'Produksi Listrik', 'nilai' => 9.11],
                        ['nama' => 'EAF', 'nilai' => 99.21],
                    ]
                ],
                [
                    'nama' => 'Inovasi Model Bisnis',
                    'nilai' => 34,
                    'indikator' => [
                        ['nama' => 'Pendapatan luar PLN', 'nilai' => 12.19],
                        ['nama' => 'Dekarbonisasi', 'nilai' => 0],
                    ]
                ],
                [
                    'nama' => 'Kepemimpinan Teknologi',
                    'nilai' => 67,
                    'indikator' => [
                        ['nama' => 'Digitalisasi', 'nilai' => 67.0],
                    ]
                ],
                [
                    'nama' => 'Peningkatan Investasi',
                    'nilai' => 42,
                    'indikator' => [
                        ['nama' => 'Capex Realization', 'nilai' => 42.0],
                    ]
                ],
                [
                    'nama' => 'Pengembangan Talenta',
                    'nilai' => 78,
                    'indikator' => [
                        ['nama' => 'Employee Engagement', 'nilai' => 78.0],
                    ]
                ],
                [
                    'nama' => 'Kepatuhan',
                    'nilai' => 91,
                    'indikator' => [
                        ['nama' => 'GCG Index', 'nilai' => 91.0],
                    ]
                ],
            ]
        ];

        return view('dashboard', compact('data'));
    }

    /**
     * Dashboard untuk admin berdasarkan peran mereka
     */
    public function admin()
    {
        $user = Auth::user();

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
        if (Auth::user()->role !== 'karyawan') {
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
        $role = Auth::user()->role;

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
