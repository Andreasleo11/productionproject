<?php

namespace App\Imports;

use App\Models\SoData;
use Maatwebsite\Excel\Concerns\ToModel;

class SoImport implements ToModel
{
    public function model(array $row)
    {
        // // Check if a record with the same doc_num and item_code already exists
        $existingRecord = SoData::where('id', $row[0])
                                ->where('doc_num', $row[1])
                                ->where('item_code', $row[4])
                                ->first();

        // If record exists, skip it by returning null
        if ($existingRecord) {
            \Log::info('Duplicate found for doc_num: ' . $row[1] . ' and item_code: ' . $row[4]);

            if ($existingRecord->is_finish == 1 || $existingRecord->is_done == 1) {
                \Log::info('Skipping record for doc_num: ' . $row[1] . ' and item_code: ' . $row[4] . ' because it is finished or done.');
                return null; // Skip this record
            }

            \Log::info('Duplicate found for doc_num: ' . $row[1] . ' and item_code: ' . $row[4] . ' in the same upload, creating a new record.');
           
            $existingRecord->update([
                'doc_num'            => $row[1],
                'customer'           => $row[2],
                'posting_date'       => $row[3],
                'item_code'          => $row[4],
                'item_name'          => $row[5],
                'quantity'           => $row[6],
                'sales_uom'          => $row[7],
                'packaging_quantity' => $row[8],
                'sales_pack'         => $row[9],
                // Leave is_finish and is_done unchanged
            ]);
            
            return $existingRecord;
        }

        return new SoData([
            'id'                 => $row[0],
            'doc_num'            => $row[1],
            'customer'           => $row[2],
            'posting_date'       => $row[3],
            'item_code'          => $row[4],
            'item_name'          => $row[5],
            'quantity'           => $row[6],
            'sales_uom'          => $row[7],
            'packaging_quantity' => $row[8],
            'sales_pack'         => $row[9],
            'is_finish'          => 0,  // Automatically set to 0
            'is_done'            => 0,  // Automatically set to 0
        ]);
    }
}
