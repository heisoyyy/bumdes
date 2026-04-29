<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\KategoriTransaksi;
use App\Models\LogTransaksi;
use App\Models\Notifikasi;
use App\Models\SaldoAwal;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::aktif()
            ->with(['kategori', 'user'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc');

        // Filter jenis
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Filter kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Filter tanggal mulai
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }

        // Filter tanggal akhir
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }

        // Search kode atau keterangan
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_transaksi', 'like', '%' . $request->search . '%')
                  ->orWhere('keterangan', 'like', '%' . $request->search . '%');
            });
        }

        $transaksi  = $query->paginate(15)->withQueryString();
        $kategoris  = KategoriTransaksi::aktif()->get();

        return view('pengurus.transaksi.index', compact(
            'transaksi',
            'kategoris',
        ));
    }

    public function create()
    {
        $kategoris = KategoriTransaksi::aktif()->get();
        return view('pengurus.transaksi.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id'     => 'required|exists:kategori_transaksi,id',
            'jenis'           => 'required|in:pemasukan,pengeluaran',
            'nominal'         => 'required|numeric|min:1',
            'tanggal'         => 'required|date',
            'keterangan'      => 'nullable|string|max:500',
            'bukti_transaksi' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'kategori_id.required' => 'Kategori wajib dipilih',
            'kategori_id.exists'   => 'Kategori tidak valid',
            'jenis.required'       => 'Jenis transaksi wajib dipilih',
            'nominal.required'     => 'Nominal wajib diisi',
            'nominal.min'          => 'Nominal minimal Rp 1',
            'tanggal.required'     => 'Tanggal wajib diisi',
            'bukti_transaksi.image'=> 'File harus berupa gambar',
            'bukti_transaksi.max'  => 'Ukuran gambar maksimal 2MB',
        ]);

        DB::beginTransaction();

        try {
            // Hitung saldo setelah transaksi
            $saldoSekarang = $this->getSaldoSekarang();

            if ($request->jenis === 'pengeluaran') {
                if ($saldoSekarang < $request->nominal) {
                    return back()
                        ->withInput()
                        ->withErrors([
                            'nominal' => 'Saldo tidak mencukupi. Saldo saat ini: Rp ' .
                                number_format($saldoSekarang, 0, ',', '.'),
                        ]);
                }
                $saldoSetelah = $saldoSekarang - $request->nominal;
            } else {
                $saldoSetelah = $saldoSekarang + $request->nominal;
            }

            // Upload bukti transaksi
            $buktiPath = null;
            if ($request->hasFile('bukti_transaksi')) {
                $buktiPath = $request->file('bukti_transaksi')
                    ->store('bukti-transaksi', 'public');
            }

            // Simpan transaksi
            $transaksi = Transaksi::create([
                'kode_transaksi'  => Transaksi::generateKode(),
                'user_id'         => Auth::id(),
                'kategori_id'     => $request->kategori_id,
                'jenis'           => $request->jenis,
                'nominal'         => $request->nominal,
                'saldo_setelah'   => $saldoSetelah,
                'tanggal'         => $request->tanggal,
                'keterangan'      => $request->keterangan,
                'bukti_transaksi' => $buktiPath,
            ]);

            // Catat log
            LogTransaksi::catat(
                transaksiId: $transaksi->id,
                userId:      Auth::id(),
                aksi:        'tambah',
                dataBaru:    $transaksi->toArray(),
                keterangan:  'Transaksi baru ditambahkan'
            );

            // Kirim notifikasi ke pengurus
            $this->kirimNotifikasi(
                'Transaksi Baru',
                'Transaksi ' . $transaksi->kode_transaksi .
                ' senilai Rp ' . number_format($request->nominal, 0, ',', '.') .
                ' telah ditambahkan'
            );

            DB::commit();

            return redirect()->route('pengurus.transaksi.index')
                ->with('success', 'Transaksi berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $transaksi = Transaksi::aktif()
            ->with(['kategori', 'user', 'logTransaksi.user'])
            ->findOrFail($id);

        return view('pengurus.transaksi.show', compact('transaksi'));
    }

    public function edit($id)
    {
        $transaksi = Transaksi::aktif()->findOrFail($id);
        $kategoris = KategoriTransaksi::aktif()->get();

        return view('pengurus.transaksi.edit', compact(
            'transaksi',
            'kategoris',
        ));
    }

    public function update(Request $request, $id)
    {
        $transaksi = Transaksi::aktif()->findOrFail($id);

        $request->validate([
            'kategori_id'     => 'required|exists:kategori_transaksi,id',
            'jenis'           => 'required|in:pemasukan,pengeluaran',
            'nominal'         => 'required|numeric|min:1',
            'tanggal'         => 'required|date',
            'keterangan'      => 'nullable|string|max:500',
            'bukti_transaksi' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $dataLama = $transaksi->toArray();

            // Hitung ulang saldo
            $saldoSekarang = $this->getSaldoSekarang($transaksi->id);

            if ($request->jenis === 'pengeluaran') {
                if ($saldoSekarang < $request->nominal) {
                    return back()
                        ->withInput()
                        ->withErrors([
                            'nominal' => 'Saldo tidak mencukupi. Saldo saat ini: Rp ' .
                                number_format($saldoSekarang, 0, ',', '.'),
                        ]);
                }
                $saldoSetelah = $saldoSekarang - $request->nominal;
            } else {
                $saldoSetelah = $saldoSekarang + $request->nominal;
            }

            // Upload bukti baru jika ada
            $buktiPath = $transaksi->bukti_transaksi;
            if ($request->hasFile('bukti_transaksi')) {
                // Hapus bukti lama
                if ($buktiPath) {
                    Storage::disk('public')->delete($buktiPath);
                }
                $buktiPath = $request->file('bukti_transaksi')
                    ->store('bukti-transaksi', 'public');
            }

            $transaksi->update([
                'kategori_id'     => $request->kategori_id,
                'jenis'           => $request->jenis,
                'nominal'         => $request->nominal,
                'saldo_setelah'   => $saldoSetelah,
                'tanggal'         => $request->tanggal,
                'keterangan'      => $request->keterangan,
                'bukti_transaksi' => $buktiPath,
            ]);

            // Catat log
            LogTransaksi::catat(
                transaksiId: $transaksi->id,
                userId:      Auth::id(),
                aksi:        'edit',
                dataLama:    $dataLama,
                dataBaru:    $transaksi->fresh()->toArray(),
                keterangan:  'Transaksi diperbarui'
            );

            DB::commit();

            return redirect()->route('pengurus.transaksi.index')
                ->with('success', 'Transaksi berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::aktif()->findOrFail($id);

        DB::beginTransaction();

        try {
            $dataLama = $transaksi->toArray();

            // Soft delete
            $transaksi->update(['deleted_at' => now()]);

            // Catat log
            LogTransaksi::catat(
                transaksiId: $transaksi->id,
                userId:      Auth::id(),
                aksi:        'hapus',
                dataLama:    $dataLama,
                keterangan:  'Transaksi dihapus'
            );

            DB::commit();

            return redirect()->route('pengurus.transaksi.index')
                ->with('success', 'Transaksi berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ============================================
    // HELPER
    // ============================================

    private function getSaldoSekarang(?int $excludeId = null): float
    {
        $saldoAwal = SaldoAwal::sum('nominal');

        $query = Transaksi::aktif();

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $totalPemasukan   = (clone $query)->pemasukan()->sum('nominal');
        $totalPengeluaran = (clone $query)->pengeluaran()->sum('nominal');

        return (float) ($saldoAwal + $totalPemasukan - $totalPengeluaran);
    }

    private function kirimNotifikasi(string $judul, string $pesan): void
    {
        // Kirim ke semua pengurus
        $pengurus = User::where('role', 'pengurus')
            ->where('is_active', 'aktif')
            ->get();

        foreach ($pengurus as $user) {
            Notifikasi::create([
                'user_id' => $user->id,
                'judul'   => $judul,
                'pesan'   => $pesan,
            ]);
        }
    }
}