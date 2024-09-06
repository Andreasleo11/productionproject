<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDailyItemCodeRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MasterListItem;
use App\Models\DailyItemCode;
use App\Models\SpkMaster;

class DailyItemCodeController extends Controller
{
    public function index()
    {
        $machines = User::where('specification_id', 2)
            ->with(['dailyItemCode' => function ($query) {
                $query->where(function ($query) {
                    $query->where('is_done', 0)
                        ->orWhereNull('is_done');
                });
            }])
            ->get();
        return view('daily-item-codes.index', compact('machines'));
    }

    public function create()
    {
        $machines = User::where('specification_id', 2)
            ->with(['dailyItemCode' => function ($query) {
                $query->where(function ($query) {
                    $query->where('is_done', 0)
                        ->orWhereNull('is_done');
                });
            }])
            ->get();
        $itemcodes = MasterListItem::get();
        return view('daily-item-codes.create', compact('machines', 'itemcodes'));
    }

    public function store(StoreDailyItemCodeRequest $request)
    {
        // The validated data can be accessed via $request->validated()
        $validatedData = $request->validated();

        // Custom validation for start and end times
        foreach ($validatedData['start_times'] as $index => $startTime) {
            $endTime = $validatedData['end_times'][$index];
            if (strtotime($endTime) <= strtotime($startTime)) {
                return back()->withErrors([
                    "end_times.$index" => 'End time must be after the start time for shift ' . $validatedData['shifts'][$index],
                ])->withInput()->with('error', 'There were errors in your form submission. Please correct them and try again.');
            }

            // Additional validation to ensure shifts are sequential
            if ($index > 0 && isset($validatedData['end_times'][$index - 1])) {
                // Only check if not the first shift and previous end time exists
                $previousEndTime = strtotime($validatedData['end_times'][$index - 1]);
                $currentStartTime = strtotime($startTime);

                if ($previousEndTime >= $currentStartTime) {
                    return back()->withErrors([
                        "start_times.$index" => 'Start time for shift ' . $validatedData['shifts'][$index] . ' must be after the end time of shift ' . $validatedData['shifts'][$index - 1],
                    ])->withInput()->with('error', 'Shift start and end times must be sequential.');
                }
            }
        }

        // Save the data to the DailyItemCodes table
        foreach ($validatedData['item_codes'] as $index => $itemCode) {
            // Initialize adjustedQuantity with default value
            $adjustedQuantity = $validatedData['quantities'][$index];

            $dailyItemCode = DailyItemCode::where('user_id', $validatedData['machine_id'])
                ->where('item_code', $itemCode)
                ->first();

            if ($dailyItemCode && $dailyItemCode->loss_package_quantity > 0) {
                // Calculate the adjusted quantity
                $adjustedQuantity = $validatedData['quantities'][$index] - $dailyItemCode->loss_package_quantity;
            }

            // Create a new DailyItemCode entry with the adjusted quantity
            DailyItemCode::create([
                'schedule_date' => $validatedData['schedule_date'],
                'user_id' => $validatedData['machine_id'],
                'item_code' => $itemCode,
                'quantity' => $validatedData['quantities'][$index],
                'start_time' => $validatedData['start_times'][$index],
                'end_time' => $validatedData['end_times'][$index],
                'shift' => $validatedData['shifts'][$index],
                'actual_quantity' => $adjustedQuantity,
            ]);
        }

        return redirect()->back()->with('success', 'Daily item codes have been successfully saved.');
    }
}
