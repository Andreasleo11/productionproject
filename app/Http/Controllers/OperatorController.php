<?php

namespace App\Http\Controllers;

use App\Models\DailyItemCode;
use App\Models\MasterListItem;
use App\Models\User;
use Illuminate\Http\Request;

class OperatorController extends Controller
{
    public function index()
    {
        $items = MasterListItem::get();
        $users = User::get();
        $assignments = DailyItemCode::with('user')->get();

        return view('operatorview', compact('items', 'users', 'assignments'));
    }

    public function assignItemCode(Request $request)
    {
        // dd($request->all());
        // Validate the request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'item_code' => 'required|string|max:255',
        ]);

        // Find the user
        $user = User::find($request->user_id);

        // Check if the user already has a DailyItemCode entry
        $dailyItemCode = $user->dailyItemCode;

        if ($dailyItemCode) {
            // Update the existing record
            $dailyItemCode->update([
                'item_code' => $request->item_code,
            ]);
        } else {
            // Create a new record
            DailyItemCode::create([
                'user_id' => $user->id,
                'machine_name' => $user->name,
                'item_code' => $request->item_code,
            ]);
        }

        return redirect()->back()->with('success', 'Item code assigned successfully.');
    }
}
