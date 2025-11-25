<?php

namespace App\Http\Controllers;

use App\Models\Tabungan;
use App\Models\SetoranTabungan;
use Illuminate\Http\Request;

class SetoranController extends Controller
{
    public function store(Request $request, $tabungan_id)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:1000'
        ]);

        $tabungan = Tabungan::findOrFail($tabungan_id);

        SetoranTabungan::create([
            'tabungan_id' => $tabungan_id,
            'jumlah' => $request->jumlah
        ]);

        // update total setoran
        $tabungan->increment('total_setoran', $request->jumlah);

        return back()->with('success', 'Setoran berhasil ditambahkan');
    }
}
