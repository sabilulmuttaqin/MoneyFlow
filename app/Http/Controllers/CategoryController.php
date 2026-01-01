<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Anggaran;
use App\Models\FastRecord;
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

    // ===============================
    // BULAN AKTIF
    // ===============================
    $monthValue = $request->query('bulan', now()->format('Y-m'));
    $monthDate  = Carbon::createFromFormat('Y-m', $monthValue);

    $start = $monthDate->copy()->startOfMonth();
    $end   = $monthDate->copy()->endOfMonth();

    // ===============================
    // TRANSAKSI BULANAN (SAMA DENGAN CATAT CEPAT)
    // ===============================
    $monthlyIncome = FastRecord::where('user_id', $userId)
        ->where('type', 'income')
        ->whereBetween('created_at', [$start, $end])
        ->sum('amount');

    $monthlyExpense = FastRecord::where('user_id', $userId)
        ->where('type', 'expense')
        ->whereBetween('created_at', [$start, $end])
        ->sum('amount');

    $balance = $monthlyIncome - $monthlyExpense;

    // ===============================
    // KATEGORI + TERPAKAI
    // ===============================
    $categories = Category::where('user_id', $userId)->get();

    $categories->each(function ($category) use ($userId, $start, $end) {

        // hanya hitung pengeluaran
        $terpakai = FastRecord::where('user_id', $userId)
            ->where('type', 'expense')
            ->where('category_id', $category->id)
            ->whereBetween('created_at', [$start, $end])
            ->sum('amount');

        // inject runtime (BUKAN DB)
        $category->used = $terpakai;

        $category->progress = $category->budget > 0
            ? min(100, round(($terpakai / $category->budget) * 100))
            : 0;
    });

    // ===============================
    // DROPDOWN BULAN
    // ===============================
    $availableMonths = collect(range(0, 11))->map(fn ($i) => [
        'value' => now()->subMonths($i)->format('Y-m'),
        'label' => now()->subMonths($i)->translatedFormat('F Y'),
    ])->reverse()->values();

    // ===============================
    // VARIABEL UNTUK FE (WAJIB ADA)
    // ===============================
    $saldoMonthValue   = $monthValue;
    $expenseMonthValue = $monthValue;
    $incomeMonthValue  = $monthValue;

    $saldoMonth   = $monthDate;
    $expenseMonth = $monthDate;
    $incomeMonth  = $monthDate;

    return view('featureview.kategori.kategori', compact(
        'categories',
        'balance',
        'monthlyExpense',
        'monthlyIncome',
        'availableMonths',
        'saldoMonthValue',
        'expenseMonthValue',
        'incomeMonthValue',
        'saldoMonth',
        'expenseMonth',
        'incomeMonth',
        'monthValue'
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
