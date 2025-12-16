<?php

namespace App\Http\Controllers;

use App\Models\Anggaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RingkasanController extends Controller
{
    public function ringkasanBulanan()
    {
        // Ambil data anggaran untuk user yang sedang login
        $anggaran = Anggaran::where('user_id', Auth::id())->first();

        // Data default jika anggaran belum ditetapkan
        $dataRingkasan = [
            'persentase_pokok' => 0,
            'persentase_keinginan' => 0,
            'persentase_tabungan' => 0,
            'nominal_pokok' => 0,
            'nominal_keinginan' => 0,
            'nominal_tabungan' => 0,
            'total_anggaran' => 0,
            // Anda perlu mengambil atau menghitung data ini dari tabel lain (misalnya 'transaksi')
            // Untuk Sisa Saldo, Pengeluaran, Tabungan Riil, dan Bulan
            'pengeluaran_riil' => 'x.xxx.xxx', // Placeholder, harusnya diambil dari tabel transaksi
            'tabungan_riil' => 'x.xxx.xxx', // Placeholder, harusnya diambil dari tabel transaksi
            'sisa_saldo' => 'x.xxx.xxx', // Placeholder, harusnya diambil dari tabel saldo/transaksi
            'bulan_tahun' => 'Oktober 2025', // Placeholder, harusnya dinamis
        ];
        
        if ($anggaran) {
            $total_anggaran = $anggaran->kebutuhan_pokok + $anggaran->keinginan + $anggaran->tabungan;

            // Jika total anggaran lebih dari 0, hitung persentase
            if ($total_anggaran > 0) {
                $dataRingkasan['persentase_pokok'] = round(($anggaran->kebutuhan_pokok / $total_anggaran) * 100);
                $dataRingkasan['persentase_keinginan'] = round(($anggaran->keinginan / $total_anggaran) * 100);
                // Hitung persentase tabungan dan pastikan totalnya 100%
                $persen_tabungan = 100 - $dataRingkasan['persentase_pokok'] - $dataRingkasan['persentase_keinginan'];
                $dataRingkasan['persentase_tabungan'] = max(0, $persen_tabungan); // Pastikan tidak negatif
            } else {
                // Jika total 0, set default persentase (misalnya 34/33/33 atau 0/0/0)
                $dataRingkasan['persentase_pokok'] = 0;
                $dataRingkasan['persentase_keinginan'] = 0;
                $dataRingkasan['persentase_tabungan'] = 0;
            }

            // Simpan nominal anggaran untuk referensi di view
            $dataRingkasan['nominal_pokok'] = $anggaran->kebutuhan_pokok;
            $dataRingkasan['nominal_keinginan'] = $anggaran->keinginan;
            $dataRingkasan['nominal_tabungan'] = $anggaran->tabungan;
            $dataRingkasan['total_anggaran'] = $total_anggaran;
        }

        // Kirim data ke view
        return view('featureview.ringkasan.index', compact('dataRingkasan'));
    }
}