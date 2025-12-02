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

        return redirect()->route('dashboard')
            ->with('success', 'Catatan berhasil disimpan.');
    }

    /**
     * Show edit page.
     */
    public function edit($id)
    {
        $record = FastRecord::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('featureview.CatatCepat.edit', compact('record'));
    }

    /**
     * Update an existing record.
     */
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

        $record->update([
            'type'     => $validated['type'],
            'category' => $validated['category'],
            'name'     => $validated['name'],
            'amount'   => $validated['amount'],
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Catatan berhasil diperbarui.');
    }

    /**
     * Delete a record.
     */
    public function destroy($id)
    {
        $record = FastRecord::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $record->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Catatan berhasil dihapus.');
    }
}
