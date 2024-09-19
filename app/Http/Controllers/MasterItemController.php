<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\MasterListItem;

class MasterItemController extends Controller
{
    public function index()
    {
        return view('master-item');
    }
}
