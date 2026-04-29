<?php

namespace App\Http\Controllers\KepalaDesa;

use App\Http\Controllers\Controller;
use App\Models\LogTransaksi;
use Illuminate\Http\Request;

class LogTransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = LogTransaksi::with(['transaksi', 'user'])
            ->orderBy('created_at', 'desc');

        // Filter aksi
        if ($request->filled('aksi')) {
            $query->where('aksi', $request->aksi);
        }

        // Filter tanggal mulai
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('created_at', '>=', $request->tanggal_mulai);
        }

        // Filter tanggal akhir
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('created_at', '<=', $request->tanggal_akhir);
        }

        // Search user
        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('kepala-desa.log-transaksi.index', compact('logs'));
    }

    public function show($id)
    {
        $log = LogTransaksi::with(['transaksi', 'user'])
            ->findOrFail($id);

        return view('kepala-desa.log-transaksi.show', compact('log'));
    }

    public function filter(Request $request)
    {
        return $this->index($request);
    }
}