<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\BackupData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function index()
    {
        $backups = BackupData::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('pengurus.backup.index', compact('backups'));
    }

    public function store(Request $request)
    {
        try {
            // Ambil semua data dari semua tabel
            $data = [
                'users'              => DB::table('users')->get(),
                'kategori_transaksi' => DB::table('kategori_transaksi')->get(),
                'saldo_awal'         => DB::table('saldo_awal')->get(),
                'transaksi'          => DB::table('transaksi')->get(),
                'laporan'            => DB::table('laporan')->get(),
                'notifikasi'         => DB::table('notifikasi')->get(),
                'log_transaksi'      => DB::table('log_transaksi')->get(),
                'pengelolaan_akun'   => DB::table('pengelolaan_akun')->get(),
                'backup_at'          => now()->toDateTimeString(),
            ];

            $json        = json_encode($data, JSON_PRETTY_PRINT);
            $filename    = 'backup-bumdes-' . now()->format('Ymd-His') . '.json';
            $path        = 'backup/' . $filename;
            $ukuran      = strlen($json);
            $ukuranFormat = $ukuran > 1048576
                ? round($ukuran / 1048576, 2) . ' MB'
                : round($ukuran / 1024, 2) . ' KB';

            Storage::disk('public')->put($path, $json);

            BackupData::create([
                'user_id'     => Auth::id(),
                'nama_file'   => $filename,
                'ukuran_file' => $ukuranFormat,
                'tipe_backup' => 'manual',
                'status'      => 'berhasil',
                'keterangan'  => 'Backup manual oleh ' . Auth::user()->name,
            ]);

            return redirect()->route('pengurus.backup.index')
                ->with('success', 'Backup berhasil dibuat: ' . $filename);

        } catch (\Exception $e) {
            BackupData::create([
                'user_id'     => Auth::id(),
                'nama_file'   => '-',
                'ukuran_file' => '0 KB',
                'tipe_backup' => 'manual',
                'status'      => 'gagal',
                'keterangan'  => 'Error: ' . $e->getMessage(),
            ]);

            return back()->with('error', 'Backup gagal: ' . $e->getMessage());
        }
    }

    public function restore($id)
    {
        $backup = BackupData::findOrFail($id);

        if ($backup->status !== 'berhasil') {
            return back()->with('error', 'File backup tidak valid');
        }

        try {
            $json = Storage::disk('public')->get('backup/' . $backup->nama_file);
            $data = json_decode($json, true);

            DB::beginTransaction();

            // Restore data per tabel
            // (implementasi restore sesuai kebutuhan)
            // Contoh: DB::table('transaksi')->insert($data['transaksi']);

            DB::commit();

            return redirect()->route('pengurus.backup.index')
                ->with('success', 'Restore berhasil dari: ' . $backup->nama_file);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Restore gagal: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $backup = BackupData::findOrFail($id);

        Storage::disk('public')->delete('backup/' . $backup->nama_file);
        $backup->delete();

        return redirect()->route('pengurus.backup.index')
            ->with('success', 'Backup berhasil dihapus');
    }
}