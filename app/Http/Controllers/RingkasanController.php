<?php

namespace App\Http\Controllers;

use App\Models\Anggaran;
use App\Models\Category;
use App\Models\FastRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RingkasanController extends Controller
{

public function ringkasanBulanan(Request $request)
{
    $userId = Auth::id();

    // ===== BULAN AKTIF =====
    $monthValue = $request->query('bulan', now()->format('Y-m'));
    $monthDate  = Carbon::createFromFormat('Y-m', $monthValue);

    $start = $monthDate->copy()->startOfMonth();
    $end   = $monthDate->copy()->endOfMonth();

    // ===== AMBIL ANGGARAN =====
    $anggaran = Anggaran::where('user_id', $userId)->latest()->first();

    $anggaranKebutuhan = (int) optional($anggaran)->kebutuhan_pokok ?? 0;
    $anggaranKeinginan = (int) optional($anggaran)->keinginan ?? 0;
    $anggaranTabungan  = (int) optional($anggaran)->tabungan ?? 0;

    $totalAnggaran = $anggaranKebutuhan + $anggaranKeinginan + $anggaranTabungan;

    // ===== TERPAKAI PER JENIS =====
    $terpakaiKebutuhan = FastRecord::where('user_id', $userId)
        ->where('type', 'expense')
        ->whereBetween('created_at', [$start, $end])
        ->whereHas('category', fn ($q) => $q->where('status', 'kebutuhan pokok'))
        ->sum('amount');

    $terpakaiKeinginan = FastRecord::where('user_id', $userId)
        ->where('type', 'expense')
        ->whereBetween('created_at', [$start, $end])
        ->whereHas('category', fn ($q) => $q->where('status', 'keinginan'))
        ->sum('amount');

    $terpakaiTabungan = FastRecord::where('user_id', $userId)
        ->where('type', 'expense')
        ->whereBetween('created_at', [$start, $end])
        ->whereHas('category', fn ($q) => $q->where('status', 'tabungan'))
        ->sum('amount');

    // ===== TOTAL PEMASUKAN & PENGELUARAN =====
    $totalPemasukan = FastRecord::where('user_id', $userId)
        ->where('type', 'income')
        ->whereBetween('created_at', [$start, $end])
        ->sum('amount');

    $totalPengeluaran = FastRecord::where('user_id', $userId)
        ->where('type', 'expense')
        ->whereBetween('created_at', [$start, $end])
        ->sum('amount');

    $sisaSaldo = $totalPemasukan - $totalPengeluaran;

    // ===== PERSENTASE DONUT =====
    $persentasePokok = $totalAnggaran > 0
        ? round(($anggaranKebutuhan / $totalAnggaran) * 100)
        : 0;

    $persentaseKeinginan = $totalAnggaran > 0
        ? round(($anggaranKeinginan / $totalAnggaran) * 100)
        : 0;

    $persentaseTabungan = max(0, 100 - $persentasePokok - $persentaseKeinginan);

    // ===== DATA VIEW =====
    $dataRingkasan = [
        'bulan_tahun' => $monthDate->translatedFormat('F Y'),

        'anggaran_kebutuhan' => $anggaranKebutuhan,
        'terpakai_kebutuhan' => $terpakaiKebutuhan,

        'anggaran_keinginan' => $anggaranKeinginan,
        'terpakai_keinginan' => $terpakaiKeinginan,

        'anggaran_tabungan' => $anggaranTabungan,
        'terpakai_tabungan' => $terpakaiTabungan,

        'total_pemasukan' => $totalPemasukan,
        'total_pengeluaran' => $totalPengeluaran,
        'sisa_saldo' => $sisaSaldo,

        'persentase_pokok' => $persentasePokok,
        'persentase_keinginan' => $persentaseKeinginan,
        'persentase_tabungan' => $persentaseTabungan,
    ];

    // ===== DROPDOWN BULAN =====
    $availableMonths = collect(range(0, 11))->map(fn ($i) => [
        'value' => now()->subMonths($i)->format('Y-m'),
        'label' => now()->subMonths($i)->translatedFormat('F Y'),
    ])->reverse()->values();

    return view('featureview.ringkasan.index', compact(
        'dataRingkasan',
        'availableMonths',
        'monthValue'
    ));
}

private function getWeekRange(Carbon $month, int $week)
{
    $start = match ($week) {
        1 => $month->copy()->startOfMonth(),
        2 => $month->copy()->startOfMonth()->addDays(7),
        3 => $month->copy()->startOfMonth()->addDays(14),
        4 => $month->copy()->startOfMonth()->addDays(21),
    };

    $end = match ($week) {
        1,2,3 => $start->copy()->addDays(6),
        4 => $month->copy()->endOfMonth(),
    };

    return [$start, $end];
}

private function availableMonths()
{
    return collect(range(0, 11))->map(fn ($i) => [
        'value' => now()->subMonths($i)->format('Y-m'),
        'label' => now()->subMonths($i)->translatedFormat('F Y'),
    ])->reverse()->values();
}

public function detailMingguan(Request $request, $minggu)
{
    $userId = Auth::id();

    /* =========================
     * 1. BULAN AKTIF
     * ========================= */
    $selectedMonth = $request->get('bulan', now()->format('Y-m'));
    $monthDate     = Carbon::createFromFormat('Y-m', $selectedMonth);

    $bulanLabel = $monthDate->translatedFormat('F Y');

    /* =========================
     * 2. RANGE MINGGU
     * ========================= */
    $startOfMonth = $monthDate->copy()->startOfMonth();
    $startWeek    = $startOfMonth->copy()->addWeeks($minggu - 1);
    $endWeek      = $startWeek->copy()->addDays(6)->endOfDay();

    /* =========================
     * 3. STATUS KATEGORI (FIXED)
     * ========================= */
    $statuses = ['kebutuhan pokok', 'keinginan', 'tabungan'];

    /* =========================
     * 4. TOTAL ANGGARAN BULANAN
     * ========================= */
    $anggaranBulanan = Category::where('user_id', $userId)
        ->whereIn('status', $statuses)
        ->selectRaw('status, SUM(budget) as total')
        ->groupBy('status')
        ->pluck('total', 'status');

    /* =========================
     * 5. BAGI ANGGARAN PER MINGGU
     * ========================= */
    $anggaranMingguan = collect($statuses)->mapWithKeys(function ($status) use ($anggaranBulanan) {
        return [$status => ($anggaranBulanan[$status] ?? 0) / 4];
    });

    /* =========================
     * 6. PENGELUARAN MINGGUAN
     * ========================= */
    $pengeluaran = FastRecord::where('user_id', $userId)
        ->where('type', 'expense')
        ->whereBetween('created_at', [$startWeek, $endWeek])
        ->whereHas('category', function ($q) use ($statuses) {
            $q->whereIn('status', $statuses);
        })
        ->with('category')
        ->get()
        ->groupBy(fn ($r) => $r->category->status);

    /* =========================
     * 7. BUILD CARD (ANTI ERROR)
     * ========================= */
    $cards = collect($statuses)->map(function ($status) use ($anggaranMingguan, $pengeluaran) {

        $budget = $anggaranMingguan[$status] ?? 0;
        $used   = $pengeluaran->get($status, collect())->sum('amount');

        return [
            'kategori' => ucfirst($status),
            'total'    => $used,
            'percent'  => $budget > 0
                ? min(100, round(($used / $budget) * 100))
                : 0,
            'color'    => match ($status) {
                'kebutuhan pokok' => '#6B78FF',
                'keinginan'       => '#F59E0B',
                'tabungan'        => '#10B981',
                default           => '#9CA3AF',
            }
        ];
    });

    /* =========================
     * 8. DROPDOWN BULAN
     * ========================= */
    $availableMonths = collect(range(0, 11))->map(fn ($i) => [
        'value' => now()->subMonths($i)->format('Y-m'),
        'label' => now()->subMonths($i)->translatedFormat('F Y'),
    ])->reverse()->values();

    return view('featureview.ringkasan.detail_minggu', compact(
        'cards',
        'minggu',
        'bulanLabel',
        'availableMonths',
        'selectedMonth'
    ));
}


}