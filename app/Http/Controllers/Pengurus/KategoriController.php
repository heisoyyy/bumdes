<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\KategoriTransaksi;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = KategoriTransaksi::orderBy('jenis')
            ->orderBy('nama_kategori')
            ->paginate(15);

        return view('pengurus.kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('pengurus.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_transaksi,nama_kategori',
            'jenis'         => 'required|in:pemasukan,pengeluaran',
            'deskripsi'     => 'nullable|string|max:500',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi',
            'nama_kategori.unique'   => 'Nama kategori sudah ada',
            'jenis.required'         => 'Jenis wajib dipilih',
        ]);

        KategoriTransaksi::create([
            'nama_kategori' => $request->nama_kategori,
            'jenis'         => $request->jenis,
            'deskripsi'     => $request->deskripsi,
            'is_active'     => 'aktif',
        ]);

        return redirect()->route('pengurus.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit($id)
    {
        $kategori = KategoriTransaksi::findOrFail($id);
        return view('pengurus.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $kategori = KategoriTransaksi::findOrFail($id);

        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_transaksi,nama_kategori,' . $id,
            'jenis'         => 'required|in:pemasukan,pengeluaran',
            'deskripsi'     => 'nullable|string|max:500',
        ]);

        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'jenis'         => $request->jenis,
            'deskripsi'     => $request->deskripsi,
        ]);

        return redirect()->route('pengurus.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui');
    }

    public function toggle($id)
    {
        $kategori = KategoriTransaksi::findOrFail($id);

        $kategori->update([
            'is_active' => $kategori->is_active === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        $status = $kategori->is_active === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('pengurus.kategori.index')
            ->with('success', 'Kategori berhasil ' . $status);
    }
}