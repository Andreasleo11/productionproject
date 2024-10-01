<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDailyItemCodeRequest;
use App\Models\DailyItemCode;
use App\Models\MasterListItem;
use App\Models\SpkMaster;
use App\Models\User;
use Illuminate\Http\Request;

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

    public function calculateItem(Request $request)
    {
        $data = $request->json()->all();

        $itemCode = $data['item_code'] ?? null;
        $quantity = $data['quantity'] ?? null;

        // Fetch SPK and Master Item Data
        $datas = SpkMaster::where('item_code', $itemCode)->get();
        $master = MasterListItem::where('item_code', $itemCode)->first();

        if (!$master) {
            return response()->json(['error' => 'Invalid item code'], 400);
        }

        $stanpack = $master->standart_packaging_list;

        // Calculate the total planned and completed quantities
        $totalPlannedQuantity = $datas->sum('planned_quantity');
        $totalCompletedQuantity = $datas->sum('completed_quantity');

        // Calculate the loss package quantity
        $final = $quantity % $stanpack;
        $lossPackageQuantity = $final === 0 ? 0 : $final;

        // Calculate the maximum allowed quantity
        $maxQuantity = $totalPlannedQuantity - $totalCompletedQuantity;

        return response()->json([
            'total_planned_quantity' => $totalPlannedQuantity,
            'total_completed_quantity' => $totalCompletedQuantity,
            'loss_package_quantity' => $lossPackageQuantity,
            'max_quantity' => $maxQuantity,
        ]);
    }

    public function store(StoreDailyItemCodeRequest $request)
    {
        // The validated data can be accessed via $request->validated()
        $validatedData = $request->validated();

        // Custom validation for start and end times and dates
        foreach ($validatedData['shifts'] as $index => $shift) {  // Use $index to loop
            $startDate = $validatedData['start_dates'][$shift]; // Access arrays by $shift
            $endDate = $validatedData['end_dates'][$shift];
            $startTime = $validatedData['start_times'][$shift];
            $endTime = $validatedData['end_times'][$shift];

            // Custom validation for end time based on the relationship between start and end dates
            if ($startDate == $endDate && strtotime($endTime) <= strtotime($startTime)) {
                return back()
                    ->withErrors([
                        "end_times.$shift" => 'End time must be after the start time when the start and end dates are the same for shift ' . $shift,
                    ])
                    ->withInput()
                    ->with('error', 'There were errors in your form submission. Please correct them and try again.');
            }

            // Ensure shifts are sequential
            if ($index > 0) {
                $previousShift = $validatedData['shifts'][$index - 1];
                $previousEndTime = strtotime($validatedData['end_times'][$previousShift]);
                $previousEndDate = strtotime($validatedData['end_dates'][$previousShift]);
                $currentStartTime = strtotime($startTime);
                $currentStartDate = strtotime($startDate);

                // Check if the current shift starts after the previous shift ends
                if ($previousEndDate > $currentStartDate || ($previousEndDate == $currentStartDate && $previousEndTime >= $currentStartTime)) {
                    return back()
                        ->withErrors([
                            "start_times.$shift" => 'Start time for shift ' . $shift . ' must be after the end time of shift ' . $previousShift,
                            "start_dates.$shift" => 'Start date for shift ' . $shift . ' must be after the end date of shift ' . $previousShift,
                        ])
                        ->withInput()
                        ->with('error', 'Shift start and end times/dates must be sequential.');
                }
            }
        }

    // $previousLossPackageQuantity = 0;
    // $previousItemCode = null;
    // Save the data to the DailyItemCodes table
    foreach ($validatedData['shifts'] as $index => $shift) {  // Use $index to loop
        $itemCode = $validatedData['item_codes'][$shift];   // Access arrays by $shift
        $quantity = $validatedData['quantities'][$shift];
        $startDate = $validatedData['start_dates'][$shift];
        $endDate = $validatedData['end_dates'][$shift];
        $startTime = $validatedData['start_times'][$shift];
        $endTime = $validatedData['end_times'][$shift];

        // Fetch SPK and Master Item Data
        $datas = SpkMaster::where('item_code', $itemCode)->get();
        $master = MasterListItem::where('item_code', $itemCode)->first();
        $stanpack = $master->standart_packaging_list;

            // Calculate the total planned and completed quantities
            $totalPlannedQuantity = $datas->sum('planned_quantity');
            $totalCompletedQuantity = $datas->sum('completed_quantity');

            // Calculate the loss package quantity
            $final = $quantity % $stanpack;
            // dd($final);
            $loss_package_quantity = $final === 0 ? 0 : $final;

            // Calculate the maximum allowed quantity
            $max_quantity = $totalPlannedQuantity - $totalCompletedQuantity;
            // dd($max_quantity);
            if ($quantity > $max_quantity) {
                return redirect()
                    ->back()
                    ->with('error', "Quantity exceeds SPK with a maximum of $max_quantity.");
            }

            // Initialize adjusted quantity
            $adjustedQuantity = $quantity;


        $previousDailyItemCode = DailyItemCode::where('user_id', $validatedData['machine_id'])
        ->where('item_code', $itemCode)
        ->orderBy('id', 'desc') // Ensure we get the latest by id
        ->first();

        // dd($previousDailyItemCode);
        // If there's an unresolved loss package, adjust the current quantity
        if ($previousDailyItemCode && $previousDailyItemCode->loss_package_quantity > 0) {
            // Subtract the previous loss package quantity from the current shift's quantity
            $adjustedQuantity = $quantity - $previousDailyItemCode->loss_package_quantity;

            }

            // Store the current loss package for the next shifts (if applicable)
            $previousLossPackageQuantity = $loss_package_quantity;

            // Create a new DailyItemCode entry
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
                'start_date' => $startDate,
                'end_date' => $endDate,
                'shift' => $shift,  // Store the shift number
            ]);

        }

        return redirect()->route('daily-item-code.index')->with('success', 'Daily Item Codes assigned successfully.');
    }

}
