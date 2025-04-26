<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        // Validasi inputan
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Menyiapkan kredensial
        $credentials = $request->only('email', 'password');

        // Cek kredensial dan login
        if (Auth::attempt($credentials)) {
            // Regenerasi session untuk menghindari serangan session fixation
            $request->session()->regenerate();

            // Cek apakah role pengguna diperbolehkan
            $allowedRoles = [
                'asisten_manager',
                'pic_keuangan',
                'pic_risiko_manajemen',
                'pic_skreperusahaan',
                'pic_perencanaan_operasi',
                'pic_pengembangan_bisnis',
                'pic_human_capital',
                'pic_k3l',
                'pic_perencanaan_korporat',
                'karyawan',
            ];

            // Jika role valid, arahkan ke dashboard sesuai role
            if (in_array(Auth::user()->role, $allowedRoles)) {
                return $this->redirectBasedOnRole();
            }

            // Jika role tidak diperbolehkan
            Auth::logout();
            return redirect()->route('login')->with('error', 'Role Anda tidak diizinkan untuk login.');
        }

        // Jika login gagal
        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Email atau password salah. Silakan coba lagi.');
    }

    /**
     * Redirect berdasarkan role user
     */
    private function redirectBasedOnRole()
    {
        // Redirect sesuai role pengguna
        switch (Auth::user()->role) {
            case 'asisten_manager':
                return redirect()->route('dashboard.master');
            case 'pic_keuangan':
                return redirect()->route('dashboard.admin.keuangan');
            case 'pic_risiko_manajemen':
                return redirect()->route('dashboard.admin.risiko');
            case 'pic_skreperusahaan':
                return redirect()->route('dashboard.admin.skreperusahaan');
            case 'pic_perencanaan_operasi':
                return redirect()->route('dashboard.admin.perencanaan_operasi');
            case 'pic_pengembangan_bisnis':
                return redirect()->route('dashboard.admin.pengembangan_bisnis');
            case 'pic_human_capital':
                return redirect()->route('dashboard.admin.human_capital');
            case 'pic_k3l':
                return redirect()->route('dashboard.admin.k3l');
            case 'pic_perencanaan_korporat':
                return redirect()->route('dashboard.admin.perencanaan_korporat');
            case 'karyawan':
                return redirect()->route('dashboard.user');
            default:
                return redirect()->route('login')->with('error', 'Role Anda tidak diizinkan untuk login.');
        }
    }

    /**
     * Logout pengguna
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
