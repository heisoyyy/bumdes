<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasis = Notifikasi::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $belumDibaca = Notifikasi::where('user_id', Auth::id())
            ->belumDibaca()
            ->count();

        return view('pengurus.notifikasi.index', compact(
            'notifikasis',
            'belumDibaca',
        ));
    }

    public function read($id)
    {
        $notifikasi = Notifikasi::where('user_id', Auth::id())
            ->findOrFail($id);

        $notifikasi->tandaiDibaca();

        return redirect()->route('pengurus.notifikasi.index')
            ->with('success', 'Notifikasi ditandai sudah dibaca');
    }

    public function readAll()
    {
        Notifikasi::where('user_id', Auth::id())
            ->belumDibaca()
            ->update(['is_read' => true]);

        return redirect()->route('pengurus.notifikasi.index')
            ->with('success', 'Semua notifikasi ditandai sudah dibaca');
    }

    public function destroy($id)
    {
        $notifikasi = Notifikasi::where('user_id', Auth::id())
            ->findOrFail($id);

        $notifikasi->delete();

        return redirect()->route('pengurus.notifikasi.index')
            ->with('success', 'Notifikasi berhasil dihapus');
    }
}