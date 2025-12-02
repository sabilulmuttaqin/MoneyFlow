<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $userId = Auth::id();

        $nowYm = Carbon::now()->format('Y-m');
        $saldoMonthValue   = $request->query('saldo_month', $nowYm);
        $expenseMonthValue = $request->query('expense_month', $saldoMonthValue);
        $incomeMonthValue  = $request->query('income_month', $saldoMonthValue);

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

        $balance = $monthlyExpense = $monthlyIncome = 0;
        if (Schema::hasTable('fast_records')) {
            $baseQuery = \App\Models\FastRecord::where('user_id', $userId);

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

            $monthlyExpense = (clone $baseQuery)
                ->where('type', 'expense')
                ->whereYear('created_at', $expenseMonth->year)
                ->whereMonth('created_at', $expenseMonth->month)
                ->sum('amount');

            $monthlyIncome = (clone $baseQuery)
                ->where('type', 'income')
                ->whereYear('created_at', $incomeMonth->year)
                ->whereMonth('created_at', $incomeMonth->month)
                ->sum('amount');
        }

        $availableMonths = collect(range(0, 11))->map(function ($i) {
            $date = Carbon::now()->subMonths($i)->startOfMonth();
            return [
                'value' => $date->format('Y-m'),
                'label' => $date->translatedFormat('F Y'),
            ];
        })->reverse()->values();

        return view('featureview.kategori.kategori', compact(
            'categories',
            'balance',
            'monthlyExpense',
            'monthlyIncome',
            'saldoMonthValue',
            'expenseMonthValue',
            'incomeMonthValue',
            'availableMonths',
            'saldoMonth',
            'expenseMonth',
            'incomeMonth'
        ));
    }

    public function create()
    {
        return view('featureview.kategori.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'type'   => 'required|in:pengeluaran,pemasukan',
            'budget' => 'required',
            'status' => 'required|in:kebutuhan pokok,keinginan,tabungan',
            'icon'   => 'nullable|string|max:50',
        ]);

        // Hapus titik dari input sebelum simpan ke DB
        $validated['budget'] = (int) str_replace('.', '', $validated['budget']);

        // ===== VALIDASI DUPLIKAT =====
        // Cek apakah kategori dengan nama dan type yang sama sudah ada
        $exists = Category::where('name', $validated['name'])
            ->where('type', $validated['type'])
            ->exists();

        if ($exists) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori dengan nama dan jenis yang sama sudah ada!'
                ], 422);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Kategori dengan nama dan jenis yang sama sudah ada!');
        }

        $category = Category::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil dibuat!',
                'category' => $category
            ]);
        }

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dibuat!');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::all();
        return view('featureview.kategori.edit', compact('category', 'categories'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'type'   => 'required|in:pengeluaran,pemasukan',
            'budget' => 'required',
            'status' => 'required|in:kebutuhan pokok,keinginan,tabungan',
            'icon'   => 'nullable|string|max:50',
        ]);

        // Hapus titik dari input sebelum update DB
        $validated['budget'] = (int) str_replace('.', '', $validated['budget']);

        // ===== VALIDASI DUPLIKAT (kecuali diri sendiri) =====
        $exists = Category::where('name', $validated['name'])
            ->where('type', $validated['type'])
            ->where('id', '!=', $category->id) // Kecuali kategori yang sedang diedit
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Kategori dengan nama dan jenis yang sama sudah ada!');
        }

        $category->update($validated);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan',
            ], 404);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus!',
            'id' => $id,
        ]);
    }
}