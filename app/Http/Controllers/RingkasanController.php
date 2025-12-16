<?php

namespace App\Http\Controllers;

use App\Models\Anggaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RingkasanController extends Controller
{


public function ringkasanBulanan(Request $request)
{
    $anggaran = Anggaran::where('user_id', Auth::id())->first();

    // 1) Ambil bulan terpilih dari query (?bulan=YYYY-MM)
    //    Kalau kosong, default bulan sekarang
    $selectedMonth = $request->query('bulan', Carbon::now()->format('Y-m'));

    // 2) Parse jadi tanggal awal bulan (untuk label & nanti filter transaksi)
    $selectedDate = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();

    // Data default
    $dataRingkasan = [
        'persentase_pokok' => 0,
        'persentase_keinginan' => 0,
        'persentase_tabungan' => 0,
        'nominal_pokok' => 0,
        'nominal_keinginan' => 0,
        'nominal_tabungan' => 0,
        'total_anggaran' => 0,
        'pengeluaran_riil' => 'x.xxx.xxx',
        'tabungan_riil' => 'x.xxx.xxx',
        'sisa_saldo' => 'x.xxx.xxx',

        // 3) Ini yang dipakai tombol dropdown
        'bulan_tahun' => $selectedDate->translatedFormat('F Y'),
    ];

    if ($anggaran) {
        $total_anggaran = $anggaran->kebutuhan_pokok + $anggaran->keinginan + $anggaran->tabungan;

        if ($total_anggaran > 0) {
            $dataRingkasan['persentase_pokok'] = round(($anggaran->kebutuhan_pokok / $total_anggaran) * 100);
            $dataRingkasan['persentase_keinginan'] = round(($anggaran->keinginan / $total_anggaran) * 100);
            $persen_tabungan = 100 - $dataRingkasan['persentase_pokok'] - $dataRingkasan['persentase_keinginan'];
            $dataRingkasan['persentase_tabungan'] = max(0, $persen_tabungan);
        }

        $dataRingkasan['nominal_pokok'] = $anggaran->kebutuhan_pokok;
        $dataRingkasan['nominal_keinginan'] = $anggaran->keinginan;
        $dataRingkasan['nominal_tabungan'] = $anggaran->tabungan;
        $dataRingkasan['total_anggaran'] = $total_anggaran;
    }

    // 4) opsi bulan (12 bulan terakhir)
    $availableMonths = collect(range(0, 11))->map(function ($i) {
        $date = Carbon::now()->subMonths($i)->startOfMonth();
        return [
            'value' => $date->format('Y-m'),
            'label' => $date->translatedFormat('F Y'),
        ];
    })->reverse()->values();

    return view('featureview.ringkasan.index', compact('dataRingkasan', 'availableMonths', 'selectedMonth'));
}



    public function detailMingguan(Request $request, int $minggu)
    {
        $selectedMonth = $request->query('bulan', Carbon::now()->format('Y-m'));
        $selectedDate  = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();

        $bulanLabel = $selectedDate->translatedFormat('F Y');

        // ambil anggaran user
        $anggaran = Anggaran::where('user_id', Auth::id())->first();

        $kebutuhan = (int) optional($anggaran)->kebutuhan_pokok ?? 0;
        $keinginan = (int) optional($anggaran)->keinginan ?? 0;
        $tabungan  = (int) optional($anggaran)->tabungan ?? 0;

        // budget per minggu (dibagi 4)
        $makananWeek   = (int) round($kebutuhan / 4);
        $hiburanWeek   = (int) round($keinginan / 4);
        $transportWeek = (int) round($tabungan / 4);

        $totalWeek = $makananWeek + $hiburanWeek + $transportWeek;

        // percent (biar progress bar ada angka)
        $pct = function(int $val) use ($totalWeek) {
            return $totalWeek > 0 ? (int) round(($val / $totalWeek) * 100) : 0;
        };

        // Cards: sesuai UI gambar
        $cards = collect([
            [
                'kategori' => 'Makanan',
                'total' => $makananWeek,
                'percent' => $pct($makananWeek),
                'color' => '#6B78FF', // ungu-biru
            ],
            [
                'kategori' => 'Transport',
                'total' => $transportWeek,
                'percent' => $pct($transportWeek),
                'color' => '#D6A133', // kuning
            ],
            [
                'kategori' => 'Hiburan',
                'total' => $hiburanWeek,
                'percent' => $pct($hiburanWeek),
                'color' => '#FF6B6B', // merah
            ],
        ])->filter(fn($c) => $c['total'] > 0)->values();

        // dropdown bulan (sama seperti ringkasan)
        $availableMonths = collect(range(0, 11))->map(function ($i) {
            $date = Carbon::now()->subMonths($i)->startOfMonth();
            return [
                'value' => $date->format('Y-m'),
                'label' => $date->translatedFormat('F Y'),
            ];
        })->reverse()->values();

        // Toast: karena gak ada transaksi, kita tampilkan info budget (bukan pengeluaran)
        $toast = "Anggaran Minggu ini berdasarkan pembagian 1 bulan / 4 minggu";

        return view('featureview.ringkasan.detail_minggu', compact(
            'minggu',
            'selectedMonth',
            'bulanLabel',
            'availableMonths',
            'cards',
            'toast'
        ));
    }

}