<?php

namespace App\Http\Controllers;

use App\Models\Tabungan;
use App\Models\SetoranTabungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetoranController extends Controller
{
    public function store(Request $request, $tabungan_id)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:1000'
        ]);

        // Ambil tabungan milik user yang login
        $tabungan = Tabungan::where('user_id', Auth::id())
            ->findOrFail($tabungan_id);

        // Simpan setoran
        SetoranTabungan::create([
            'tabungan_id' => $tabungan->id,
            'jumlah' => $request->jumlah
        ]);

        // Update total setoran di tabungan
        $tabungan->increment('total_setoran', $request->jumlah);

        return redirect()
    ->route('tabungan.show', $tabungan->id)
    ->with('success_setoran', [
        'jumlah' => $request->jumlah,
        'progress' => round(
            (($tabungan->total_setoran + $request->jumlah + ($tabungan->setoran_awal ?? 0))
            / $tabungan->target) * 100
        )
    ]);

    }
}
