<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\PengelolaanAkun;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AkunMasyarakatController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'masyarakat')
            ->orderBy('name');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('username', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        $masyarakat = $query->paginate(15)->withQueryString();

        return view('pengurus.akun-masyarakat.index', compact('masyarakat'));
    }

    public function create()
    {
        return view('pengurus.akun-masyarakat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'no_hp'    => 'nullable|string|max:15',
        ], [
            'name.required'      => 'Nama wajib diisi',
            'username.required'  => 'Username wajib diisi',
            'username.unique'    => 'Username sudah digunakan',
            'email.required'     => 'Email wajib diisi',
            'email.unique'       => 'Email sudah digunakan',
            'password.required'  => 'Password wajib diisi',
            'password.min'       => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        $masyarakat = User::create([
            'name'      => $request->name,
            'username'  => $request->username,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'masyarakat',
            'no_hp'     => $request->no_hp,
            'is_active' => 'aktif',
        ]);

        // Catat log pengelolaan akun
        PengelolaanAkun::catat(
            pengurusId:   Auth::id(),
            masyarakatId: $masyarakat->id,
            aksi:         'tambah',
            keterangan:   'Akun masyarakat baru dibuat'
        );

        return redirect()->route('pengurus.akun-masyarakat.index')
            ->with('success', 'Akun masyarakat berhasil ditambahkan');
    }

    public function edit($id)
    {
        $masyarakat = User::where('role', 'masyarakat')
            ->findOrFail($id);

        return view('pengurus.akun-masyarakat.edit', compact('masyarakat'));
    }

    public function update(Request $request, $id)
    {
        $masyarakat = User::where('role', 'masyarakat')
            ->findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,' . $id,
            'email'    => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'no_hp'    => 'nullable|string|max:15',
        ]);

        $dataUpdate = [
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'no_hp'    => $request->no_hp,
        ];

        if ($request->filled('password')) {
            $dataUpdate['password'] = Hash::make($request->password);
        }

        // Upload foto profil
        if ($request->hasFile('foto_profil')) {
            if ($masyarakat->foto_profil) {
                Storage::disk('public')->delete($masyarakat->foto_profil);
            }
            $dataUpdate['foto_profil'] = $request->file('foto_profil')
                ->store('foto-profil', 'public');
        }

        $masyarakat->update($dataUpdate);

        // Catat log
        PengelolaanAkun::catat(
            pengurusId:   Auth::id(),
            masyarakatId: $masyarakat->id,
            aksi:         'edit',
            keterangan:   'Data akun masyarakat diperbarui'
        );

        return redirect()->route('pengurus.akun-masyarakat.index')
            ->with('success', 'Akun masyarakat berhasil diperbarui');
    }

    public function toggle($id)
    {
        $masyarakat = User::where('role', 'masyarakat')
            ->findOrFail($id);

        $statusBaru = $masyarakat->is_active === 'aktif' ? 'nonaktif' : 'aktif';
        $masyarakat->update(['is_active' => $statusBaru]);

        // Catat log
        PengelolaanAkun::catat(
            pengurusId:   Auth::id(),
            masyarakatId: $masyarakat->id,
            aksi:         $statusBaru === 'aktif' ? 'aktifkan' : 'nonaktifkan',
            keterangan:   'Status akun diubah menjadi ' . $statusBaru
        );

        $pesan = $statusBaru === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('pengurus.akun-masyarakat.index')
            ->with('success', 'Akun masyarakat berhasil ' . $pesan);
    }
}