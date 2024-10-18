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
        $users = User::all();
        $dailyItemCodes = DailyItemCode::all();
        $itemCodes = MasterListItem::all()->pluck('item_code');
        // dd($dailyItemCodes);
        return view('daily-item-codes.index', compact('dailyItemCodes', 'users', 'itemCodes'));
    }

    public function create(Request $request)
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

        $selectedDate = $request->selected_date;
        $machineId = $request->machine_id;

        $selectedMachine = User::where('id', $machineId)->first();

        // Transform $selectedMachine->name using the helper function
        $transformedMachineName = $this->transformUsername($selectedMachine->name);

        // Use the transformed machine name to query the MasterListItem
        $masterListItem = MasterListItem::get();

        return view('daily-item-codes.create', compact('machines', 'masterListItem', 'selectedDate', 'selectedMachine'));
    }

    private function transformUsername($username) {
        // Use regular expression to match the numeric part and the alphabetic part separately
        if (preg_match('/^0*(\d+)([A-Z])$/', $username, $matches)) {
            // $matches[1] will have the numeric part without leading zeros
            // $matches[2] will have the alphabetic part
            return $matches[1] . $matches[2];
        }

        // If the username doesn't match the expected pattern, return it unchanged
        return $username;
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

        // dd($request->all());

        // The validated data can be accessed via $request->validated()
        $validatedData = $request->validated();

        // Custom validation for start and end times and dates
        foreach ($validatedData['shifts'] as $index => $shift) {
            // Use $shift to access specific data for this shift
            $startDate = $validatedData['start_dates'][$shift][0]; // First entry for shift
            $endDate = $validatedData['end_dates'][$shift][0];
            $startTime = $validatedData['start_times'][$shift][0];
            $endTime = $validatedData['end_times'][$shift][0];

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
                $previousEndTime = strtotime($validatedData['end_times'][$previousShift][0]);
                $previousEndDate = strtotime($validatedData['end_dates'][$previousShift][0]);
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

        // Save the data to the DailyItemCodes table
        foreach ($validatedData['shifts'] as $shift) {
            // Loop through the item codes for each shift
            foreach ($validatedData['item_codes'][$shift] as $key => $itemCode) {
                $quantity = $validatedData['quantities'][$shift][$key];
                $startDate = $validatedData['start_dates'][$shift][$key];
                $endDate = $validatedData['end_dates'][$shift][$key];
                $startTime = $validatedData['start_times'][$shift][$key];
                $endTime = $validatedData['end_times'][$shift][$key];

                // Fetch SPK and Master Item Data
                $datas = SpkMaster::where('item_code', $itemCode)->get();
                $master = MasterListItem::where('item_code', $itemCode)->first();
                $stanpack = $master->standart_packaging_list;

                // Calculate the total planned and completed quantities
                $totalPlannedQuantity = $datas->sum('planned_quantity');
                $totalCompletedQuantity = $datas->sum('completed_quantity');

                // Calculate the loss package quantity
                $final = $quantity % $stanpack;
                $loss_package_quantity = $final === 0 ? 0 : $final;

                // Calculate the maximum allowed quantity
                $max_quantity = $totalPlannedQuantity - $totalCompletedQuantity;
                if ($quantity > $max_quantity) {
                    return back()
                        ->withInput()
                        ->with('error', "Quantity of $itemCode exceeds SPK with a maximum of $max_quantity.");
                }

                // Initialize adjusted quantity
                $adjustedQuantity = $quantity;

                // Check for unresolved loss package from previous entries
                $previousDailyItemCode = DailyItemCode::where('user_id', $validatedData['machine_id'])
                    ->where('item_code', $itemCode)
                    ->orderBy('id', 'desc') // Ensure we get the latest by id
                    ->first();

                if ($previousDailyItemCode && $previousDailyItemCode->loss_package_quantity > 0) {
                    // Adjust the current quantity
                    $adjustedQuantity = $quantity - $previousDailyItemCode->loss_package_quantity;
                }

                // Store the new DailyItemCode entry
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
                    'shift' => $shift, // Store the shift number
                ]);
            }
        }

        return redirect()->route('daily-item-code.index')->with('success', 'Daily Item Codes assigned successfully.');
    }

    public function daily(Request $request)
    {
        $selectedDate = $request->query('date'); // Get the date from the query parameter
        $machines = User::where('specification_id', 2)
            ->with([
                'dailyItemCode' => function ($query) {
                    $query->where(function ($query) {
                        $query->where('is_done', 0)->orWhereNull('is_done');
                    });
                },
            ])
            ->get();
        return view('daily-item-codes.daily', compact('selectedDate', 'machines'));
    }

    public function update(Request $request, $id){
        // dd($request->all());
        $validatedData = $request->validate([
            'item_code' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'shift' => 'required|integer',
            'start_date' => 'required|date',
            'start_time' => 'required',
            'end_date' => 'required|date',
            'end_time' => 'required',
        ]);

        $dailyItemCode = DailyItemCode::findOrFail($id);
        $dailyItemCode->update($validatedData);

        return redirect()->back()->with('success', 'Daily Item Code updated successfully.');
    }
}
