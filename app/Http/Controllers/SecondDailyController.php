<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SecondDailyProcess;
use App\Models\MasterAllItem;

class SecondDailyController extends Controller
{
    public function index()
    {
        return view('second.index');
    }

    public function create()
    {
        $schedule = SecondDailyProcess::get();

        return view('second.create');
    }

    public function searchItems(Request $request)
{
    $query = $request->input('query');
    $items = MasterAllItem::where('item_code', 'like', "%{$query}%")
            ->orWhere('item_description', 'like', "%{$query}%")
            ->limit(50) // Limit results to improve performance
            ->get();

    return response()->json($items);
}

    public function store(Request $request)
    {
        $request->validate([
            'plan_date' => 'required|date',
            'pic' => 'required|string|max:255',
            'shift' => 'required|integer',
            'line.*' => 'required|string|max:255',
            'item_code.*' => 'required|string|max:255',
            'item_description.*' => 'required|string|max:255',
            'quantity_hour.*' => 'nullable|integer',
            'process_time.*' => 'nullable|numeric',
            'quantity_plan.*' => 'nullable|integer',
            'customer.*' => 'required|string|max:255',
        ]);

        // Loop over each repeatable entry
        foreach ($request->line as $index => $line) {
            Planning::create([
                'plan_date' => $request->plan_date,
                'pic' => $request->pic,
                'shift' => $request->shift,
                'line' => $line,
                'item_code' => $request->item_code[$index],
                'item_description' => $request->item_description[$index],
                'quantity_hour' => $request->quantity_hour[$index] ?? null,
                'process_time' => $request->process_time[$index] ?? null,
                'quantity_plan' => $request->quantity_plan[$index] ?? null,
                'customer' => $request->customer[$index],
            ]);
        }

        return redirect()->route('planning.index')->with('success', 'Planning entries created successfully.');
    }
}
