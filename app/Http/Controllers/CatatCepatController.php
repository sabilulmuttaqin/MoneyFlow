<?php

namespace App\Http\Controllers;

use App\Models\FastRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CatatCepatController extends Controller
{
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
}
