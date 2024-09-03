<?php

namespace App\Http\Controllers;

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
        $itemcodes = MasterListItem::get();
     
        
        return view('daily-item-codes.index', compact('machines','itemcodes'));
    }

    public function applyItemCode(Request $request, $machine_id)
    {
        // Dump and Die the incoming request data (used for debugging)
        // dd($request->all());

        // Validate the incoming request data
        $request->validate([
            'user_id' => 'required|exists:users,id', // Ensure user_id exists in the users table
            'item_codes.*' => 'required|exists:master_list_items,item_code', // Ensure each item_code exists in master_list_items table
            'quantities.*' => 'required|integer|min:1', // Ensure each quantity is a positive integer
        ]);

        

        // Loop through each item code and its corresponding quantity
        foreach ($request->input('item_codes') as $index => $itemCode) {
            $quantity = $request->input('quantities')[$index];
          
            $itemcode = $request->input('item_codes')[$index];
            $datas = SpkMaster::where('item_code', $itemCode)->get();
            $stanpack = MasterListItem::where('item_code', $itemCode)->get();
            
            $totalPlannedQuantity = $datas->sum('planned_quantity');
            $totalCompletedQuantity = $datas->sum('completed_quantity');

            $final = $quantity % $stanpack;
            $finalquantity = $quantity;
            if($final === 0 )
            {
                $loss_package_quantity = 0;
            }
            else{
                $loss_package_quantity = $quantity - $final;
            }

            // Calculate the difference
            $max_quantity = $totalPlannedQuantity - $totalCompletedQuantity;
            // dd($max_quantity);
            if($quantity > $max_quantity)
            {
                return redirect()->back()->with('alert', "Quantity exceeds SPK with a maximum of $max_quantity.");
            }
            else{

                $spks = SpkMaster::where('item_code', $itemCode)->orderBy('post_date')->get();
    
                $remainingQuantity = $quantity;
            
                foreach ($spks as $spk) {
                    // Calculate the available quantity to complete
                    $availableQuantity = $spk->planned_quantity - $spk->completed_quantity;
            
                    if ($remainingQuantity <= 0) {
                        break;
                    }
            
                    if ($remainingQuantity > $availableQuantity) {
                        // If remaining quantity is greater than available, complete this SPK
                        $spk->completed_quantity = $spk->planned_quantity;
                        $remainingQuantity -= $availableQuantity;
                    } else {
                        // If remaining quantity is less or equal, just add to completed_quantity and finish
                        $spk->completed_quantity += $remainingQuantity;
                        $remainingQuantity = 0;
                    }
            
                    // Save the SPK with the updated completed quantity
                    $spk->save();
                }

            

                // Find an existing record with the same user_id and item_code
                $dailyItemCode = DailyItemCode::where('user_id', $request->input('user_id'))
                                            ->where('item_code', $itemCode)
                                            ->first();

                if ($dailyItemCode) {
                    // If the record exists, update the quantity
                    if ($dailyItemCode->loss_package_quantity > 0) {
                        // Calculate the adjusted quantity
                        $adjustedQuantity = $quantity - $dailyItemCode->loss_package_quantity;
            
                        // Create a new record with the adjusted quantity
                        DailyItemCode::create([
                            'user_id' => $request->input('user_id'),
                            'item_code' => $itemCode,
                            'quantity' => $adjustedQuantity,
                            'final_quantity' => $finalquantity,
                            'loss_package_quantity' => 0,
                            'actual_quantity' => $adjustedQuantity,
                        ]);
            
                        dd('New record created with adjusted quantity due to loss package.');
                    } else {
                        // If loss_package_quantity is not greater than 0, handle normally
                        DailyItemCode::create([
                            'user_id' => $request->input('user_id'),
                            'item_code' => $itemCode,
                            'quantity' => $quantity,
                            'final_quantity' => $finalquantity,
                            'loss_package_quantity' => $loss_package_quantity,
                            'actual_quantity' => $quantity,
                        ]);
                    }
                    
                } else {
                    // If no record exists, create a new one
                    DailyItemCode::create([
                        'user_id' => $request->input('user_id'),
                        'item_code' => $itemCode,
                        'quantity' => $quantity,
                        'final_quantity' => $finalquantity,
                        'loss_package_quantity' => $loss_package_quantity,
                        'actual_quantity' => $quantity,
                    ]);
                }
            }
        }

        // Redirect back to the index page with a success message
        return redirect()->route('daily-item-code.index')->with('success', 'Item codes and quantities applied successfully.');
    }
}
