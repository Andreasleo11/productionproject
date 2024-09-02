<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SoData;
use App\Models\ScannedData;

class SOController extends Controller
{
    public function index()
    {
        $docNums = SoData::select('doc_num')->distinct()->get();
        // dd($docNums);
        return view('soindex', compact('docNums'));
    }

    public function process($docNum)
    {
        $allFinished = SoData::where('doc_num', $docNum)
            ->where(function ($query) {
                $query->where('is_finish', false)
                    ->orWhereNull('is_finish'); // Handle null values as well
            })
            ->doesntExist(); // If no such record exists, then all are finished

        $allDone = SoData::where('doc_num', $docNum)
            ->where(function ($query) {
                $query->where('is_done', false)
                    ->orWhereNull('is_done'); // Handle null values as well
            })
            ->doesntExist(); // If no such record exists, then all are done

        $data = SoData::with('scannedData')->where('doc_num', $docNum)
            ->get()
            ->groupBy('item_code')
            ->map(function ($group) use ($docNum) {
                // Sum the quantities for each item_code
                $totalQuantity = $group->sum('quantity');

                // Keep one entry and update the quantity
                $entry = $group->first();
                $entry->quantity = $totalQuantity;

                $scannedCount = ScannedData::where('doc_num', $docNum)
                    ->where('item_code', $entry->item_code)
                    ->count();

                $entry->scannedCount = $scannedCount;

                $packagingQuantity = $entry->packaging_quantity; // Assuming `packaging_quantity` is a field in your SoData model

                // Check if scannedCount equals quantity / packaging_quantity and update is_finish
                if ($packagingQuantity > 0 && $scannedCount === ($totalQuantity / $packagingQuantity)) {
                    $entry->is_finish = 1;
                } else {
                    $entry->is_finish = 0; // Optionally set is_finish to 0 if the condition is not met
                }

                return $entry;
            })
            ->values(); // Reset the keys after grouping
        foreach ($data as $entry) {
            SoData::where('id', $entry->id)->update([
                'is_finish' => $entry->is_finish
            ]);
        }
        // dd($data);
        $date = $data->isNotEmpty() ? $data->first()->posting_date : null;
        $customer = $data->isNotEmpty() ? $data->first()->customer : null;
        // Pass the data to the view

        $scandatas = ScannedData:: // Order by item_code
            orderBy('label')  // Then order by label
            ->get()
            ->groupBy('item_code');
        return view('soresults', compact('data', 'docNum', 'date', 'customer', 'scandatas', 'allFinished', 'allDone'));
    }

    public function scanBarcode(Request $request)
    {
        $request->validate([
            'item_code' => 'required|string',
            'quantity' => 'required|integer',
            'warehouse' => 'required|string',
            'label' => 'required|integer',
        ]);

        $doc_num = $request->input('so_number');
        $item_code = $request->input('item_code');
        $quantity = $request->input('quantity');
        $warehouse = $request->input('warehouse');
        $label = $request->input('label');

        // Fetch the item data
        $item = SoData::where('item_code', $item_code)->first();

        if (!$item) {
            return redirect()->back()->withErrors(['error' => 'Item not found']);
        }

        $existingScans = ScannedData::where('item_code', $item_code)->get();
        $scannedTotalQuantity = $existingScans->sum('quantity');

        if ($scannedTotalQuantity >= $item->quantity) {
            return redirect()->back()->withErrors(['error' => 'All required CTN have been scanned']);
        }

        // Check if the scanned data already exists
        $existingScan = ScannedData::where('item_code', $item_code)
            ->where('label', $label)
            ->first();

        if ($existingScan) {
            return redirect()->back()->withErrors(['error' => 'Data already scanned']);
        }

        // Add new scanned data
        ScannedData::create([
            'doc_num' => $doc_num,
            'item_code' => $item_code,
            'quantity' => $quantity,
            'warehouse' => $warehouse,
            'label' => $label,
        ]);

        return redirect()->back()->with('success', 'Barcode scanned successfully');
    }



    public function updateSoData($docNum)
    {
        // Update all SoData records with the given docNum
        SoData::where('doc_num', $docNum)
            ->update(['is_done' => true]);

        // Redirect back with a success message or to another route
        return redirect()->route('so.index')->with('status', 'All records updated successfully.');
    }
}
