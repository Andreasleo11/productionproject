<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\MasterListItem;

class MasterItemController extends Controller
{
    public function index()
    {
        // Number of items per page
        $itemsPerPage = 10;

        // Paginate the items
        $items = MasterListItem::paginate($itemsPerPage);

        // Fetch all files (if you need to paginate files as well, adjust accordingly)
        $files = File::all();

        return view('master-item', compact('files', 'items'));
    }
}
