<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DailyItemCodeController extends Controller
{
    public function index()
    {
        return view('daily-item-codes.index');
    }
}
