<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\File; 

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // dd($user);
        // Check if the user's specification_id is 2
        if ($user->specification_id == 2) {
           
            // Check if the user has a related dailyItemCode and retrieve the item_code
            $itemCode = $user->dailyItemCode->item_code ?? null;

            // If an item_code exists, retrieve all files with the same item_code
            if ($itemCode) {
                $files = File::where('item_code', $itemCode)->get();
            } else {
                $files = collect(); // Return an empty collection if no item_code is found
            }
        
        } else {
            $files = collect(); // Return an empty collection if specification_id is not 2
        }
        // dd($files);
        return view('dashboard', compact('files'));
    }
}
