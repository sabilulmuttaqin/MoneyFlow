<?php

namespace App\Http\Controllers;

use App\Models\Anggaran;
use Illuminate\Http\Request;

class AnggaranController extends Controller
{
    public function index()
    {
        $anggarans = Anggaran::all();  // Ambil semua data anggaran
        return view('anggaran.index', compact('anggarans'));
    }

    public function create()
    {
        return view('anggaran.create');  // Tampilan untuk tambah anggaran
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string',
            'prosentase' => 'required|numeric|min:0|max:100',
            'nominal' => 'required|numeric|min:0',
        ]);

        Anggaran::create([
            'kategori' => $request->kategori,
            'prosentase' => $request->prosentase,
            'nominal' => $request->nominal,
        ]);

        return redirect()->route('anggaran.index')->with('success', 'Anggaran berhasil dibuat!');
    }

    // Bisa tambah edit, update, destroy jika diperlukan
}
