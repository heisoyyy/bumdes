<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\SaldoAwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaldoAwalController extends Controller
{
    public function index()
    {
        $saldoAwal     = SaldoAwal::with('user')
            ->orderBy('tanggal', 'desc')
            ->paginate(15);

        $totalSaldoAwal = SaldoAwal::sum('nominal');

        return view('pengurus.saldo-awal.index', compact(
            'saldoAwal',
            'totalSaldoAwal',
        ));
    }

    public function create()
    {
        return view('pengurus.saldo-awal.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nominal'    => 'required|numeric|min:1',
            'tanggal'    => 'required|date',
            'keterangan' => 'nullable|string|max:500',
        ], [
            'nominal.required' => 'Nominal wajib diisi',
            'nominal.min'      => 'Nominal minimal Rp 1',
            'tanggal.required' => 'Tanggal wajib diisi',
        ]);

        SaldoAwal::create([
            'user_id'    => Auth::id(),
            'nominal'    => $request->nominal,
            'tanggal'    => $request->tanggal,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('pengurus.saldo-awal.index')
            ->with('success', 'Saldo awal berhasil ditambahkan');
    }

    public function edit($id)
    {
        $saldoAwal = SaldoAwal::findOrFail($id);
        return view('pengurus.saldo-awal.edit', compact('saldoAwal'));
    }

    public function update(Request $request, $id)
    {
        $saldoAwal = SaldoAwal::findOrFail($id);

        $request->validate([
            'nominal'    => 'required|numeric|min:1',
            'tanggal'    => 'required|date',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $saldoAwal->update([
            'nominal'    => $request->nominal,
            'tanggal'    => $request->tanggal,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('pengurus.saldo-awal.index')
            ->with('success', 'Saldo awal berhasil diperbarui');
    }
}