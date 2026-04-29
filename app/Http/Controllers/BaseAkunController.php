<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class BaseAkunController extends Controller
{
    protected string $dashboardRoute;
    protected string $akunRoute;
    protected string $viewPrefix;
    protected string $sidebarView;

    public function index(Request $request)
    {
        $user = Auth::user();

        // Support query ?tab=password dari navbar
        if ($request->query('tab') === 'password') {
            session()->flash('tab', 'password');
        }

        return view($this->viewPrefix . '.akun.index', compact('user'));
    }

    public function updateProfil(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'       => 'required|string|max:100',
            'username'   => 'required|string|max:50|unique:users,username,' . $user->id,
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'no_hp'      => 'nullable|string|max:15',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'name.required'     => 'Nama wajib diisi',
            'username.required' => 'Username wajib diisi',
            'username.unique'   => 'Username sudah digunakan',
            'email.required'    => 'Email wajib diisi',
            'email.unique'      => 'Email sudah digunakan',
            'foto_profil.image' => 'File harus berupa gambar',
            'foto_profil.max'   => 'Ukuran gambar maksimal 2MB',
        ]);

        $dataUpdate = [
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'no_hp'    => $request->no_hp,
        ];

        // Upload foto profil baru
        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            $dataUpdate['foto_profil'] = $request->file('foto_profil')
                ->store('foto-profil', 'public');
        }

        $user->update($dataUpdate);

        return redirect()->route($this->akunRoute)
            ->with('success', 'Profil berhasil diperbarui');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'password_lama'         => 'required|string',
            'password_baru'         => 'required|string|min:8|confirmed',
        ], [
            'password_lama.required'         => 'Password lama wajib diisi',
            'password_baru.required'         => 'Password baru wajib diisi',
            'password_baru.min'              => 'Password baru minimal 8 karakter',
            'password_baru.confirmed'        => 'Konfirmasi password tidak cocok',
        ]);

        // Cek password lama
        if (!Hash::check($request->password_lama, $user->password)) {
            return back()
                ->withErrors(['password_lama' => 'Password lama tidak sesuai'])
                ->with('tab', 'password');
        }

        // Cek password baru tidak sama dengan lama
        if (Hash::check($request->password_baru, $user->password)) {
            return back()
                ->withErrors(['password_baru' => 'Password baru tidak boleh sama dengan password lama'])
                ->with('tab', 'password');
        }

        $user->update([
            'password' => Hash::make($request->password_baru),
        ]);

        return redirect()->route($this->akunRoute)
            ->with('success', 'Password berhasil diperbarui')
            ->with('tab', 'password');
    }

    public function hapusFoto()
    {
        $user = Auth::user();

        if ($user->foto_profil) {
            Storage::disk('public')->delete($user->foto_profil);
            $user->update(['foto_profil' => null]);
        }

        return redirect()->route($this->akunRoute)
            ->with('success', 'Foto profil berhasil dihapus');
    }
}
