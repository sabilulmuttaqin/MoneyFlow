<?php

namespace App\Http\Controllers;

use App\Models\Anggaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Auth

class AnggaranController extends Controller
{
    public function index()
    {
        // Ambil data anggaran untuk user yang sedang login, jika ada
        $anggaran = Anggaran::where('user_id', Auth::id())->first();

        // Jika data belum ada, buat objek Anggaran kosong dengan default 0
        if (!$anggaran) {
            $anggaran = new Anggaran([
                'kebutuhan_pokok' => 0,
                'keinginan' => 0,
                'tabungan' => 0,
            ]);
        }

        return view('featureview.anggaran.anggaran', compact('anggaran'));
    }

    // Ganti fungsi store menjadi storeOrUpdate untuk menangani penyimpanan dan pembaruan
    public function storeOrUpdate(Request $request)
    {
        $user_id = Auth::id();

        // Validasi input
        $request->validate([
            'kebutuhan_pokok' => 'required|numeric|min:0',
            'keinginan' => 'required|numeric|min:0',
            'tabungan' => 'required|numeric|min:0',
        ]);

        // Cari Anggaran berdasarkan user_id. Jika tidak ada, buat baru.
        // Konsep UPSERT (Update or Insert)
        Anggaran::updateOrCreate(
            ['user_id' => $user_id],
            [
                'kebutuhan_pokok' => $request->kebutuhan_pokok,
                'keinginan' => $request->keinginan,
                'tabungan' => $request->tabungan,
            ]
        );

        return redirect()->route('ringkasan.bulanan')->with('success', 'Anggaran berhasil disimpan atau diperbarui!');
    }

    // Hapus fungsi create() karena tidak digunakan lagi
    // Hapus fungsi store() yang lama
}