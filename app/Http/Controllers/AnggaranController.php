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
        $userId = Auth::id();
        $bulan  = now()->format('Y-m'); // contoh: 2025-12

        $request->validate([
            'kebutuhan_pokok' => 'required|numeric|min:0',
            'keinginan'       => 'required|numeric|min:0',
            'tabungan'        => 'required|numeric|min:0',
        ]);

        Anggaran::updateOrCreate(
            [
                'user_id' => $userId,
                'bulan'   => $bulan, // 🔑 KUNCI UTAMA
            ],
            [
                'kebutuhan_pokok' => $request->kebutuhan_pokok,
                'keinginan'       => $request->keinginan,
                'tabungan'        => $request->tabungan,
            ]
        );

        return redirect()
            ->route('ringkasan.bulanan')
            ->with('success', 'Anggaran bulan ' . now()->translatedFormat('F Y') . ' berhasil disimpan');
    }

    public function copyLastMonth()
    {
        $userId = Auth::id();

        // Ambil anggaran bulan lalu
        $lastMonth = now()->subMonth()->format('Y-m');

        $last = Anggaran::where('user_id', $userId)
            ->where('bulan', $lastMonth)
            ->first();

        // Kalau bulan lalu BELUM ADA → balik tanpa error
        if (!$last) {
            return redirect()->route('dashboard')
                ->with('error', 'Anggaran bulan lalu belum tersedia.');
        }

        // Copy ke bulan ini
        Anggaran::updateOrCreate(
            [
                'user_id' => $userId,
                'bulan'   => now()->format('Y-m'),
            ],
            [
                'kebutuhan_pokok' => $last->kebutuhan_pokok,
                'keinginan'       => $last->keinginan,
                'tabungan'        => $last->tabungan,
            ]
        );

        return redirect()->route('dashboard')
            ->with('success', 'Anggaran bulan ini berhasil dibuat dari bulan lalu.');
    }


    // Hapus fungsi create() karena tidak digunakan lagi
    // Hapus fungsi store() yang lama
}