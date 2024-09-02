<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MasterListItem;
use App\Models\DailyItemCode;

class DailyItemCodeController extends Controller
{
    public function index()
    {
        $machines = User::where('specification_id', 2)->with('dailyItemCode')->get();
        $itemcodes = MasterListItem::get();
        
        return view('daily-item-codes.index', compact('machines','itemcodes'));
    }

    public function applyItemCode(Request $request, $machine_id)
    {
         // Validate the incoming request data
         $request->validate([
            'user_id' => 'required|exists:users,id', // Ensure user_id exists in the users table
            'item_code' => 'required|exists:master_list_items,item_code', // Ensure item_code_id exists in master_list_items table
        ]);

        $dailyItemCode = DailyItemCode::where('user_id', $request->input('user_id'))->first();

        if ($dailyItemCode) {
            // If the record exists, update the item_code
            $dailyItemCode->update([
                'item_code' => $request->input('item_code'),
            ]);
        } else {
            // If no record exists, create a new one
            DailyItemCode::create([
                'user_id' => $request->input('user_id'),
                'item_code' => $request->input('item_code'),
            ]);
        }
    
        // Redirect back to the index page with a success message
        return redirect()->route('daily-item-code.index')->with('success', 'Item code applied successfully.');
    }
}
