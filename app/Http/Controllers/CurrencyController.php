<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::all();
        return view('currencies.index', compact('currencies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'code' => 'required|string',
            'sign' => 'required|string',
        ]);

        Currency::create($validated);
        return back()->with('success', 'Currency added successfully.');
    }

    public function update(Request $request, Currency $currency)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'code' => 'required|string',
            'sign' => 'required|string',
        ]);

        $currency->update($validated);
        return back()->with('success', 'Currency updated successfully.');
    }

    public function destroy(Currency $currency)
    {
        $currency->delete();
        return back()->with('success', 'Currency deleted successfully.');
    }
}
