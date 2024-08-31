<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterListItem;
use Milon\Barcode\DNS1D;

class InitialBarcodeController extends Controller
{
    public function index()
    {
        $tipeMesins = MasterListItem::distinct()->pluck('tipe_mesin');
        
        return view('barcode.index', compact('tipeMesins'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'tipe_mesin' => 'required',
        ]);

        // Fetch items with the selected tipe_mesin
        $items = MasterListItem::where('tipe_mesin', $request->tipe_mesin)->get();
        
        return view('barcode.generate', compact('items'));
    }
}
