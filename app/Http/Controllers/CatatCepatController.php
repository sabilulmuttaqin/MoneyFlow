<?php

namespace App\Http\Controllers;

use App\Models\FastRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CatatCepatController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Ambil bulan yang dipilih dari dropdown (default bulan ini)
        $selectedMonth = $request->get('month', now()->format('Y-m'));

        // Parse bulan untuk filter
        $monthStart = Carbon::parse($selectedMonth . '-01')->startOfMonth();
        $monthEnd = Carbon::parse($selectedMonth . '-01')->endOfMonth();

        // Query transaksi BERDASARKAN BULAN YANG DIPILIH
        $transactions = FastRecord::where('user_id', $user->id)
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung income dan expense BULAN INI
        $monthlyIncome = FastRecord::where('user_id', $user->id)
            ->where('type', 'income')
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->sum('amount');

        $monthlyExpense = FastRecord::where('user_id', $user->id)
            ->where('type', 'expense')
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->sum('amount');

        $balance = $monthlyIncome - $monthlyExpense;

        // Buat list bulan untuk dropdown (6 bulan terakhir)
        $availableMonths = [];
        for ($i = 0; $i < 6; $i++) {
            $date = now()->subMonths($i);
            $availableMonths[] = [
                'value' => $date->format('Y-m'),
                'label' => $date->locale('id')->isoFormat('MMMM YYYY')
            ];
        }

        return view('featureview.home.listcatatcepat', compact(
            'transactions',
            'balance',
            'monthlyExpense',
            'monthlyIncome',
            'availableMonths',
            'selectedMonth'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'     => 'required|in:expense,income',
            'category' => 'required|string|max:100',
            'name'     => 'required|string|max:255',
            'amount'   => 'required|numeric|min:0.01',
        ]);

        FastRecord::create([
            'user_id'  => Auth::id(),
            'type'     => $validated['type'],
            'category' => $validated['category'],
            'name'     => $validated['name'],
            'amount'   => $validated['amount'],
        ]);

        return redirect()->route('dashboard')->with('success', 'Catatan berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'type'     => 'required|in:expense,income',
            'category' => 'required|string|max:100',
            'name'     => 'required|string|max:255',
            'amount'   => 'required|numeric|min:0.01',
        ]);

        $record = FastRecord::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $record->update($validated);

        return redirect()->back()->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $record = FastRecord::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $record->delete();

        return redirect()->back()->with('success', 'Transaksi berhasil dihapus.');
    }
}
