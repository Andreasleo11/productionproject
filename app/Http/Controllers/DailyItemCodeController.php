<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDailyItemCodeRequest;
use App\Models\DailyItemCode;
use App\Models\MasterListItem;
use App\Models\SpkMaster;
use App\Models\User;

class DailyItemCodeController extends Controller
{
    public function index()
    {
        $machines = User::where('specification_id', 2)
            ->with([
                'dailyItemCode' => function ($query) {
                    $query->where(function ($query) {
                        $query->where('is_done', 0)->orWhereNull('is_done');
                    });
                },
            ])
            ->get();

        return view('daily-item-codes.index', compact('machines'));
    }

    public function create()
    {
        $machines = User::where('specification_id', 2)
            ->with([
                'dailyItemCode' => function ($query) {
                    $query->where(function ($query) {
                        $query->where('is_done', 0)->orWhereNull('is_done');
                    });
                },
            ])
            ->get();
        $itemcodes = MasterListItem::get();

        return view('daily-item-codes.create', compact('machines', 'itemcodes'));
    }

    public function store(StoreDailyItemCodeRequest $request)
    {
        // The validated data can be accessed via $request->validated()
        $validatedData = $request->validated();

        // dd($validatedData);

        // Custom validation for start and end times
        foreach ($validatedData['shifts'] as $index => $shift) {
            $startTime = $validatedData['start_times'][$shift];
            $endTime = $validatedData['end_times'][$shift];

            if (strtotime($endTime) <= strtotime($startTime)) {
                return back()
                    ->withErrors([
                        "end_times.$shift" => 'End time must be after the start time for shift '.$shift,
                    ])
                    ->withInput()
                    ->with('error', 'There were errors in your form submission. Please correct them and try again.');
            }

            // Additional validation to ensure shifts are sequential
            if ($index > 0) {
                $previousShift = $validatedData['shifts'][$index - 1];
                $previousEndTime = strtotime($validatedData['end_times'][$previousShift]);
                $currentStartTime = strtotime($startTime);

                if ($previousEndTime >= $currentStartTime) {
                    return back()
                        ->withErrors([
                            "start_times.$shift" => 'Start time for shift '.$shift.' must be after the end time of shift '.$previousShift,
                        ])
                        ->withInput()
                        ->with('error', 'Shift start and end times must be sequential.');
                }
            }
        }

        // Save the data to the DailyItemCodes table
        foreach ($validatedData['shifts'] as $index => $shift) {
            $itemCode = $validatedData['item_codes'][$shift];
            $quantity = $validatedData['quantities'][$shift];

            $datas = SpkMaster::where('item_code', $itemCode)->get();
            $master = MasterListItem::where('item_code', $itemCode)->first();
            $stanpack = $master->standart_packaging_list;

            $totalPlannedQuantity = $datas->sum('planned_quantity');
            $totalCompletedQuantity = $datas->sum('completed_quantity');

            $final = $quantity % $stanpack;
            $finalQuantity = $quantity;

            $loss_package_quantity = $final === 0 ? 0 : $quantity - $final;

            // Calculate the maximum allowed quantity
            $max_quantity = $totalPlannedQuantity - $totalCompletedQuantity;
            if ($quantity > $max_quantity) {
                return redirect()
                    ->back()
                    ->with('alert', "Quantity exceeds SPK with a maximum of $max_quantity.");
            }

            // Initialize adjustedQuantity with default value
            $adjustedQuantity = $quantity;

            $dailyItemCode = DailyItemCode::where('user_id', $validatedData['machine_id'])
                ->where('item_code', $itemCode)
                ->first();

            if ($dailyItemCode && $dailyItemCode->loss_package_quantity > 0) {
                // Calculate the adjusted quantity
                $adjustedQuantity = $quantity - $dailyItemCode->loss_package_quantity;
            }

            // Create a new DailyItemCode entry with the adjusted quantity
            DailyItemCode::create([
                'schedule_date' => $validatedData['schedule_date'],
                'user_id' => $validatedData['machine_id'],
                'item_code' => $itemCode,
                'quantity' => $quantity,
                'loss_package_quantity' => $loss_package_quantity,
                'final_quantity' => $quantity,
                'actual_quantity' => $adjustedQuantity,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'shift' => $shift,
            ]);
        }

        return redirect()->back()->with('success', 'Daily item codes have been successfully saved.');
    }
}
