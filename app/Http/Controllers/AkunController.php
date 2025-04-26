<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AkunController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('akun.index', compact('users'));
    }

    public function create()
    {
        return view('akun.create');
    }

    public function store(Request $request)
    {
        $allowedRoles = [
            'asisten_manager',
            'pic_keuangan',
            'pic_risiko_manajemen',
            'pic_skretaris_perusahaan',
            'pic_perencanaan_operasi',
            'pic_pengembangan_bisnis',
            'pic_human_capital',
            'pic_k3l',
            'pic_perencanaan_korporat',
            'karyawan',
        ];

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:' . implode(',', $allowedRoles),
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('akun.index')->with('success', 'Akun berhasil dibuat.');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('akun.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('akun.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $allowedRoles = [
            'asisten_manager',
            'pic_keuangan',
            'pic_risiko_manajemen',
            'pic_skretaris_perusahaan',
            'pic_perencanaan_operasi',
            'pic_pengembangan_bisnis',
            'pic_human_capital',
            'pic_k3l',
            'pic_perencanaan_korporat',
            'karyawan',
        ];

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:' . implode(',', $allowedRoles),
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('akun.index')->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('akun.index')->with('success', 'Akun berhasil dihapus.');
    }
}
