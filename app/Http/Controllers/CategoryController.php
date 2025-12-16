<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Anggaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CategoryController extends Controller
{
    // INDEX
    public function index(Request $request)
    {
        $userId = Auth::id();
        $categories = Category::where('user_id', $userId)->get();

        $nowYm = Carbon::now()->format('Y-m');
        $saldoMonthValue   = $request->query('saldo_month', $nowYm);
        $expenseMonthValue = $request->query('expense_month', $saldoMonthValue);
        $incomeMonthValue  = $request->query('income_month', $saldoMonthValue);

        $parseMonth = fn(string $value) => Carbon::createFromFormat('Y-m', $value)->startOfMonth();

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

        $availableMonths = collect(range(0, 11))->map(fn($i) => [
            'value' => Carbon::now()->subMonths($i)->format('Y-m'),
            'label' => Carbon::now()->subMonths($i)->translatedFormat('F Y')
        ])->reverse()->values();

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

    // CREATE
    public function create()
    {
        return view('featureview.kategori.create');
    }

    // STORE
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:pengeluaran,pemasukan',
            'budget' => 'required|numeric|min:0',
            'status' => 'required|in:kebutuhan pokok,keinginan,tabungan',
            'icon' => 'nullable|string|max:50',
        ]);

        $validated['budget'] = (int) str_replace('.', '', $validated['budget']);
        $validated['user_id'] = Auth::id();

        // Ambil anggaran_id otomatis
        $anggaran = Anggaran::where('user_id', Auth::id())->latest()->first();
        $validated['anggaran_id'] = $anggaran ? $anggaran->id : null;

        // Cek duplikat
        $exists = Category::where('name', $validated['name'])
            ->where('type', $validated['type'])
            ->where('user_id', Auth::id())
            ->exists();

        if ($exists) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori dengan nama dan jenis yang sama sudah ada!'
                ], 422);
            }

            return redirect()->back()->withInput()->with('error', 'Kategori dengan nama dan jenis yang sama sudah ada!');
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

    // EDIT
    public function edit(Category $category)
    {
        if ($category->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Ambil semua kategori user untuk grid
        $categories = Category::where('user_id', Auth::id())->get();

        return view('featureview.kategori.edit', compact('category', 'categories'));
    }

    // UPDATE
    public function update(Request $request, Category $category)
    {
        if ($category->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:pengeluaran,pemasukan',
            'budget' => 'required|numeric|min:0',
            'status' => 'required|in:kebutuhan pokok,keinginan,tabungan',
            'icon' => 'nullable|string|max:50',
        ]);

     

        // Cek duplikat kecuali diri sendiri
        $exists = Category::where('name', $validated['name'])
            ->where('type', $validated['type'])
            ->where('user_id', Auth::id())
            ->where('id', '!=', $category->id)
            ->exists();

        if ($exists) {
            return redirect()->back()->withInput()->with('error', 'Kategori dengan nama dan jenis yang sama sudah ada!');
        }

        $category->update($validated);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    // DESTROY
    public function destroy(Category $category)
    {
        if ($category->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus!',
            'id' => $category->id,
        ]);
    }
}
