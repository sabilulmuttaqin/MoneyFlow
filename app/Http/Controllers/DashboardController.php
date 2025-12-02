<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FastRecord;
use App\Models\Tabungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId    = Auth::id();

        // default bulan = bulan sekarang (YYYY-MM)
        $nowYm = Carbon::now()->format('Y-m');

        // 1) baca 3 bulan berbeda dari query
        $saldoMonthValue   = $request->query('saldo_month', $nowYm);
        $expenseMonthValue = $request->query('expense_month', $saldoMonthValue);
        $incomeMonthValue  = $request->query('income_month', $saldoMonthValue);

        // helper parse "YYYY-MM" ke Carbon
        $parseMonth = function (string $value) {
            try {
                return Carbon::createFromFormat('Y-m', $value)->startOfMonth();
            } catch (\Exception $e) {
                return Carbon::now()->startOfMonth();
            }
        };

        $saldoMonth   = $parseMonth($saldoMonthValue);
        $expenseMonth = $parseMonth($expenseMonthValue);
        $incomeMonth  = $parseMonth($incomeMonthValue);

        $saldoMonthLabel   = $saldoMonth->translatedFormat('F Y');
        $expenseMonthLabel = $expenseMonth->translatedFormat('F Y');
        $incomeMonthLabel  = $incomeMonth->translatedFormat('F Y');

        if (Schema::hasTable('fast_records')) {
            $baseQuery = FastRecord::where('user_id', $userId);

            // 2) saldo = pemasukan - pengeluaran pada saldoMonth
            $saldoIncome = (clone $baseQuery)
                ->where('type', 'income')
                ->whereYear('created_at', $saldoMonth->year)
                ->whereMonth('created_at', $saldoMonth->month)
                ->sum('amount');

            $saldoExpense = (clone $baseQuery)
                ->where('type', 'expense')
                ->whereYear('created_at', $saldoMonth->year)
                ->whereMonth('created_at', $saldoMonth->month)
                ->sum('amount');

            $balance = $saldoIncome - $saldoExpense;

            // 3) pengeluaran bulanan (expenseMonth)
            $monthlyExpense = (clone $baseQuery)
                ->where('type', 'expense')
                ->whereYear('created_at', $expenseMonth->year)
                ->whereMonth('created_at', $expenseMonth->month)
                ->sum('amount');

            // 4) pemasukan bulanan (incomeMonth)
            $monthlyIncome = (clone $baseQuery)
                ->where('type', 'income')
                ->whereYear('created_at', $incomeMonth->year)
                ->whereMonth('created_at', $incomeMonth->month)
                ->sum('amount');

            // 5) recent activity (global, tidak terpengaruh dropdown)
            $recentActivities = (clone $baseQuery)
                ->orderBy('created_at', 'desc')
                ->take(7)
                ->get();

            // 6) template "gunakan data terakhir" dipisah per tipe (max 3)
            $lastExpenseTemplates = (clone $baseQuery)
                ->where('type', 'expense')
                ->orderBy('created_at', 'desc')
                ->get()
                ->unique(fn($item) => $item->category . '-' . $item->name)
                ->take(3)
                ->values();

            $lastIncomeTemplates = (clone $baseQuery)
                ->where('type', 'income')
                ->orderBy('created_at', 'desc')
                ->get()
                ->unique(fn($item) => $item->category . '-' . $item->name)
                ->take(3)
                ->values();

            // 7) chart batang: 7 hari terakhir dari hari ini (global)
            $chartRaw   = [];
            $maxValue   = 0;
            $chartStart = Carbon::now()->subDays(6)->startOfDay();

            for ($i = 0; $i < 7; $i++) {
                $date = (clone $chartStart)->addDays($i);

                $incomeDay = (clone $baseQuery)
                    ->where('type', 'income')
                    ->whereDate('created_at', $date)
                    ->sum('amount');

                $expenseDay = (clone $baseQuery)
                    ->where('type', 'expense')
                    ->whereDate('created_at', $date)
                    ->sum('amount');

                $maxValue = max($maxValue, $incomeDay, $expenseDay);

                $chartRaw[] = [
                    'label'   => $date->isoFormat('D'),
                    'income'  => $incomeDay,
                    'expense' => $expenseDay,
                ];
            }

            $chartData = collect($chartRaw)->map(function ($item) use ($maxValue) {
                if ($maxValue <= 0) {
                    $incomePercent  = 0;
                    $expensePercent = 0;
                } else {
                    $incomePercent  = max(5, round(($item['income']  / $maxValue) * 90));
                    $expensePercent = max(5, round(($item['expense'] / $maxValue) * 90));
                }

                return [
                    'label'           => $item['label'],
                    'income'          => $item['income'],
                    'expense'         => $item['expense'],
                    'income_percent'  => $incomePercent,
                    'expense_percent' => $expensePercent,
                ];
            });

        } else {
            // Jika tabel fast_records belum ada
            $saldoIncome = $saldoExpense = $balance = $monthlyExpense = $monthlyIncome = 0;
            $recentActivities = collect();
            $lastExpenseTemplates = collect();
            $lastIncomeTemplates = collect();
            $chartData = collect();
        }

        // 8) tabungan (global, tidak terpengaruh dropdown)
        $tabunganList = Tabungan::where('user_id', $userId)->get();

        // 9) opsi bulan (12 bulan terakhir)
        $availableMonths = collect(range(0, 11))->map(function ($i) {
            $date = Carbon::now()->subMonths($i)->startOfMonth();
            return [
                'value' => $date->format('Y-m'),
                'label' => $date->translatedFormat('F Y'),
            ];
        })->reverse()->values();

        return view('featureview.home.dashboard', compact(
            'balance',
            'monthlyIncome',
            'monthlyExpense',
            'recentActivities',
            'chartData',
            'tabunganList',
            'saldoMonthValue',
            'expenseMonthValue',
            'incomeMonthValue',
            'saldoMonthLabel',
            'expenseMonthLabel',
            'incomeMonthLabel',
            'availableMonths',
            'lastExpenseTemplates',
            'lastIncomeTemplates'
        ));
    }
}
