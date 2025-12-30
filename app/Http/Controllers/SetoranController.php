<?php

namespace App\Http\Controllers;

use App\Models\Tabungan;
use App\Models\SetoranTabungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SetoranController extends Controller
{
    public function store(Request $request, $tabungan_id)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:1000',
        ]);

        // Ambil tabungan milik user yang login
        $tabungan = Tabungan::where('user_id', Auth::id())
            ->findOrFail($tabungan_id);

        DB::transaction(function () use ($request, $tabungan) {

            SetoranTabungan::create([
                'tabungan_id' => $tabungan->id,
                'user_id'     => Auth::id(),
                'jumlah'      => $request->jumlah,
                'tipe'        => 'setor',
            ]);

        });

        return redirect()
            ->route('tabungan.show', $tabungan->id)
            ->with('success_setoran', [
                'jumlah' => $request->jumlah,
            ]);
    }
}
