<?php

namespace App\Http\Controllers;

use App\Models\Tabungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TabunganController extends Controller
{
    public function index()
    {
        $tabungan = Tabungan::where('user_id', Auth::id())->get();

        return view('featureview.Tabungan.index', compact('tabungan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'target' => 'required|numeric',
            'setoran_awal' => 'nullable|numeric',
            'tenggat' => 'nullable|date'
        ]);

        Tabungan::create([
            'user_id' => Auth::id(),
            'nama' => $request->nama,
            'target' => $request->target,
            'setoran_awal' => $request->setoran_awal,
            'total_setoran' => 0,
            'tenggat' => $request->tenggat
        ]);

        return back()->with('success', 'Tabungan berhasil ditambahkan');
    }

    public function show($id)
    {
        $tabungan = Tabungan::where('user_id', Auth::id())->findOrFail($id);
        $setoran = $tabungan->setoran()->latest()->get();

        return view('tabungan.detail', compact('tabungan', 'setoran'));
    }

    public function update(Request $request, $id)
    {
    $request->validate([
        'nama' => 'required|string',
        'target' => 'required|numeric',
        'setoran_awal' => 'required|numeric'
    ]);

    $tabungan = Tabungan::where('user_id', Auth::id())->findOrFail($id);

    $tabungan->update([
        'nama' => $request->nama,
        'target' => $request->target,
        'setoran_awal' => $request->setoran_awal
    ]);

    return redirect()->back()->with('success', 'Tabungan berhasil diperbarui!');
    }



    public function destroy($id)
    {
    Tabungan::findOrFail($id)->delete();

    return redirect()->back()->with('success', 'Tabungan berhasil dihapus!');
    }   

}
