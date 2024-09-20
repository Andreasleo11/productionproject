<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\File;
use App\Models\DailyItemCode;
use App\Models\MachineJob;
use App\Models\MasterListItem;
use App\Models\SpkMaster;
use Milon\Barcode\DNS1D;
use App\Models\ProductionScannedData;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $uniquedata = null;
        $files = collect();
        $itemCode = null;
        // dd($user);
        // Check if the user's specification_id is 2
        if ($user->specification_id == 2) {
            $datas = DailyItemCode::where('user_id', $user->id)->with('masterItem')->get();
            foreach ($datas as $data) {
                $quantity = $data->first()->quantity;
                //    dd($quantity);
                // Check if the user has a related dailyItemCode and retrieve the item_code
                $itemCode = $user->jobs->item_code ?? null;

                // If an item_code exists, retrieve all files with the same item_code
                if ($itemCode) {
                    $files = File::where('item_code', $itemCode)->get();
                    // $this->scanFromProduction($itemCode, $datas);
                    $datasnew = SpkMaster::where('item_code', $itemCode)->get();
                    $masteritem = MasterListItem::where('item_code', $itemCode)->get()->first();
                    $perpack = $masteritem->standart_packaging_list;
                    $label = (int) ceil($quantity / $perpack);
                    $uniquedata = [];
                    $labelstart = 0;
                    $previous_spk = null; // Variable to track the previous SPK
                    $start_label = null; // Variable to store start_label for each SPK
                    foreach ($datasnew as $data) {
                        $available_quantity = $data->planned_quantity - $data->completed_quantity;
                        if ($quantity <= $available_quantity) {
                            $available_quantity = $quantity;
                        }
                        $deficit = 0;
                        if ($data->completed_quantity === 0) {
                            $labelstart = 0;
                        } else {
                            $labelstart = ceil($data->completed_quantity / $perpack);
                        }

                        if ($deficit != 0) {
                            $available_quantity -= $deficit;
                            $deficit = 0;
                        }

                        while ($available_quantity > 0) {
                            if ($available_quantity >= $perpack) {
                                // Assign a full label to this SPK
                                $labelstart++;
                                $labels[] = [
                                    'spk' => $data->spk_number,
                                    'item_code' => $data->item_code,
                                    'warehouse' => 'FG',
                                    'quantity' => $perpack,
                                    'label' => $labelstart,
                                ];

                                // Check if SPK has changed
                                if ($previous_spk !== $data->spk_number) {
                                    // If SPK has changed, set start_label and reset end_label
                                    $start_label = $labelstart;
                                    $previous_spk = $data->spk_number;
                                }

                                $key = $data->spk_number . '|' . $data->item_code;
                                if (isset($uniquedata[$key])) {
                                    $uniquedata[$key]['count']++;
                                    $uniquedata[$key]['end_label'] = $labelstart; // Update end_label as it progresses
                                } else {
                                    $uniquedata[$key] = [
                                        'spk' => $data->spk_number,
                                        'item_code' => $data->item_code,
                                        'item_perpack' => $perpack,
                                        'available_quantity' => $available_quantity,
                                        'count' => 1,
                                        'start_label' => $start_label, // Set start_label for this SPK
                                        'end_label' => $labelstart, // Initially, end_label is the same as start_label
                                    ];
                                }

                                $available_quantity -= $perpack;
                                $quantity -= $perpack;
                            } else {
                                // Assign a partial label to this SPK and move to the next
                                $labelstart++;
                                $labels[] = [
                                    'spk' => $data->spk_number,
                                    'item_code' => $data->item_code,
                                    'warehouse' => 'FG',
                                    'quantity' => $perpack,
                                    'label' => $labelstart,
                                ];

                                $key = $data->spk_number . '|' . $data->item_code;
                                if (isset($uniquedata[$key])) {
                                    $uniquedata[$key]['count']++;
                                    $uniquedata[$key]['end_label'] = $labelstart; // Update end_label for partial labels
                                } else {
                                    $uniquedata[$key] = [
                                        'spk' => $data->spk_number,
                                        'item_code' => $data->item_code,
                                        'item_perpack' => $perpack,
                                        'available_quantity' => $available_quantity,
                                        'count' => 1,
                                        'start_label' => $start_label,
                                        'end_label' => $labelstart,
                                    ];
                                }
                                $deficit = $available_quantity;
                                $available_quantity = 0;
                            }
                        }
                    }

                    // Convert uniquedata to array format
                    $uniquedata = array_values($uniquedata);
                    // dd($uniquedata);
                    foreach ($uniquedata as &$data) {
                        // Query the production_scanned_data table for matching spk and item_code
                        $scannedCount = ProductionScannedData::where('spk_code', $data['spk'])
                            ->where('item_code', $data['item_code'])
                            ->count();

                        // Add the scannedData count to the current $data array
                        $data['scannedData'] = $scannedCount;
                    }
                    // dd($uniquedata);

                }
            }
        }
        // dd($files);
        if ($user->name === 'Administrator' || $user->name === 'PE' || $user->name === 'Store') {
            return view('dashboard', compact('files'));
        } else {
            return view('dashboard', compact('files', 'datas', 'itemCode', 'uniquedata'));
            // return view('dashboard', compact('files'));
        }
    }

    public function updateMachineJob(Request $request)
    {
        $request->validate([
            'item_code' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $itemCode = $request->input('item_code');

        $verified_data = DailyItemCode::where('user_id', $user->id)->get();
        $itemCodeExists = $verified_data->contains('item_code', $itemCode);
        // dd($itemCodeExists);
        if ($itemCodeExists) {
            // Find the machine job record related to the user
            $machineJob = MachineJob::where('user_id', $user->id)->first();

            if ($machineJob) {
                // Update the machine job with the new item_code
                $machineJob->item_code = $itemCode;
                $machineJob->save();

                return redirect()->back()->with('success', 'Machine job updated successfully.');
            } else {
                return redirect()->back()->with('error', 'Machine job not found.');
            }
        } else {
            return redirect()->back()->with('error', 'Item code does not exist for the user.');
        }
    }

    //generate barcode for each item_code
    public function itemCodeBarcode($item_code, $quantity)
    {

        $datas = SpkMaster::where('item_code', $item_code)->get();
        $masteritem = MasterListItem::where('item_code', $item_code)->first();
       
        $perpack = $masteritem->standart_packaging_list;
        $label = (int) ceil($quantity / $perpack);
        $uniquedata = [];
        $previous_spk = null; // Variable to track the previous SPK
        $start_label = null; // Variable to store start_label for each SPK

        foreach ($datas as $data) {
            $available_quantity = $data->planned_quantity - $data->completed_quantity;
            if ($quantity <= $available_quantity) {
                $available_quantity = $quantity;
            }
            $deficit = 0;
            if ($data->completed_quantity === 0) {
                $labelstart = 0;
            } else {
                $labelstart = ceil($data->completed_quantity / $perpack);
            }

            if ($deficit != 0) {
                $available_quantity -= $deficit;
                $deficit = 0;
            }

            while ($available_quantity > 0) {
                if ($available_quantity >= $perpack) {
                    // Assign a full label to this SPK
                    $labelstart++;
                    $labels[] = [
                        'spk' => $data->spk_number,
                        'item_code' => $data->item_code,
                        'item_name' => $masteritem->item_name,
                        'warehouse' => 'FG',
                        'quantity' => $perpack,
                        'label' => $labelstart,
                    ];

                    // Check if SPK has changed
                    if ($previous_spk !== $data->spk_number) {
                        // If SPK has changed, set start_label and reset end_label
                        $start_label = $labelstart;
                        $previous_spk = $data->spk_number;
                    }

                    $key = $data->spk_number . '|' . $data->item_code;
                    if (isset($uniquedata[$key])) {
                        $uniquedata[$key]['count']++;
                        $uniquedata[$key]['end_label'] = $labelstart; // Update end_label as it progresses
                    } else {
                        $uniquedata[$key] = [
                            'spk' => $data->spk_number,
                            'item_code' => $data->item_code,
                            'item_name' => $masteritem->item_name,
                            'count' => 1,
                            'start_label' => $start_label, // Set start_label for this SPK
                            'end_label' => $labelstart, // Initially, end_label is the same as start_label
                        ];
                    }

                    $available_quantity -= $perpack;
                    $quantity -= $perpack;
                } else {
                    // Assign a partial label to this SPK and move to the next
                    $labelstart++;
                    $labels[] = [
                        'spk' => $data->spk_number,
                        'item_code' => $data->item_code,
                        'item_name' => $masteritem->item_name,
                        'warehouse' => 'FG',
                        'quantity' => $perpack,
                        'label' => $labelstart,
                    ];

                    $key = $data->spk_number . '|' . $data->item_code;
                    if (isset($uniquedata[$key])) {
                        $uniquedata[$key]['count']++;
                        $uniquedata[$key]['end_label'] = $labelstart; // Update end_label for partial labels
                    } else {
                        $uniquedata[$key] = [
                            'spk' => $data->spk_number,
                            'item_code' => $data->item_code,
                            'item_name' => $masteritem->item_name,
                            'count' => 1,
                            'start_label' => $start_label,
                            'end_label' => $labelstart,
                        ];
                    }
                    $deficit = $available_quantity;
                    $available_quantity = 0;
                }
            }
        }

        // Convert uniquedata to array format
        $uniquedata = array_values($uniquedata);

        // dd($uniquedata);

        $barcodeGenerator = new DNS1D();
        $barcodes = [];
        foreach ($labels as $labelData) {
            // First barcode with all data
            $barcodeData1 = implode("\t", [
                $labelData['spk'],
                $labelData['item_code'],
                $labelData['warehouse'],
                $labelData['quantity'],
                $labelData['label']
            ]);

            // Second barcode with subset of data
            $barcodeData2 = implode("\t", [
                $labelData['item_code'],
                $labelData['warehouse'],
                $labelData['quantity'],
                $labelData['label']
            ]);

           
            //BARCODE SIZE IS 1 , 25 
            
            $barcodes[] = [
                'first' => $barcodeGenerator->getBarcodeHTML($barcodeData1, 'C128', 1, 50),
                'second' => $barcodeGenerator->getBarcodeHTML($barcodeData2, 'C128', 1, 55)
            ];
        }

        return view('barcodeMachineJob', compact('labels', 'barcodes'));
    }

    public function procesProductionBarcodes(Request $request)
    {
        // dd($request->all());
        $datas = json_decode($request->input('datas'));
        $uniquedata = json_decode($request->input('uniqueData'));
        // dd($uniquedata);
        // dd($datas);
        // dd($uniquedata);
        $request->validate([
            'spk_code' => 'required|string',
            'item_code' => 'required|string',
            'warehouse' => 'required|string',
            'quantity' => 'required|integer',
            'label' => 'required|string',
        ]);

        // Perform initial validation
        $validator = Validator::make($request->all(), [
            'spk_code' => 'required|string',
            'item_code' => 'required|string',
            'warehouse' => 'required|string',
            'quantity' => 'required|integer',
            'label' => 'required|string',
        ]);

        // Add additional validation after the main validation
        $validator->after(function ($validator) use ($request, $uniquedata) {
            $spk_code = $request->input('spk_code');
            $item_code = $request->input('item_code');
            $quantity = $request->input('quantity');
            $label = $request->input('label');

            // Check if spk_code and item_code exist in $uniquedata
            $found = null;
            foreach ($uniquedata as $data) {
                if ($data->spk === $spk_code && $data->item_code === $item_code) {
                    $found = $data;
                    // dd($found);
                    break;
                }
            }

            // If not found, add validation error
            if (!$found) {
                $validator->errors()->add('spk_code', 'The provided SPK code or item code does not exist.');
            } else {
                // dd('masuk sini');
                // Check if the label is within the valid range
                $start_label = (int) $found->start_label;
                $end_label = (int) $found->end_label;
                // dd($start_label);
                // dd($end_label);
                if ($label < $start_label || $label > $end_label) {
                    // dd('out dari label');
                    $validator->errors()->add('label', "The label must be between $start_label and $end_label for SPK $spk_code and item code $item_code.");
                };
                // dd('dalam range label');
            }
        });
        // dd('pass');
        // If validation fails, return errors
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $spk_code = $request->input('spk_code');
        $item_code = $request->input('item_code');
        $quantity = $request->input('quantity');
        $warehouse = $request->input('warehouse');
        $label = $request->input('label');


        $existingScan = ProductionScannedData::where('spk_code', $spk_code)->where('item_code', $item_code)
            ->where('label', $label)
            ->first();

        if ($existingScan) {
            return redirect()->back()->withErrors(['error' => 'Data already scanned']);
        }
        // dd('aman sampe sini ?');
        ProductionScannedData::create([
            'spk_code' => $spk_code,
            'item_code' => $item_code,
            'quantity' => $quantity,
            'warehouse' => $warehouse,
            'label' => $label,
        ]);

        return redirect()->back();
    }

    public function resetJobs(Request $request)
    {
        $uniquedata = json_decode($request->input('uniquedata'), true);
        dd($uniquedata);
        // Get the currently authenticated user
        $user = auth()->user();

        // Find all jobs related to the user
        $jobs = MachineJob::where('user_id', $user->id)->get();

        // Loop through the jobs and reset the item_code (or other relevant fields)
        foreach ($jobs as $job) {
            $job->item_code = null; // Or any default value you'd like
            $job->save(); // Save the changes to the database
        }

        // Optionally return a message or redirect the user
        return redirect()->back()->with('success', 'Jobs have been reset successfully.');
    }
}
