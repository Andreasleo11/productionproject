<?php

namespace App\Imports;

use App\Models\SoData;
use Maatwebsite\Excel\Concerns\ToModel;

class SoImport implements ToModel
{
    public function model(array $row)
    {

        $amendRecordhard = SoData::where('id', $row[0])
                         ->where('doc_num', '!=', $row[1])
                         ->first();

        
        // If the record exists, update it with the new data
        if ($amendRecordhard) {
            \Log::info('Found record with different doc_num and item_code. Updating...');

            $amendRecordhard->update([
                'doc_num'            => $row[1],
                'customer'           => $row[2],
                'posting_date'       => $row[3],
                'item_code'          => $row[4],  // Update to new item_code from the Excel file
                'item_name'          => $row[5],
                'quantity'           => $row[6],
                'sales_uom'          => $row[7],
                'packaging_quantity' => $row[8],
                'sales_pack'         => $row[9],
                'create_date'        => $row[10],
                'update_date'        => $row[11],
                'update_fulltime'    => $row[12],
                'is_finish'          => $amendRecordhard->is_finish,  // Keep is_finish unchanged
                'is_done'            => $amendRecordhard->is_done,    // Keep is_done unchanged
            ]);

            return $amendRecordhard;  // Return the updated record
        }


        $amendRecord = SoData::where('id', $row[0])
                         ->where('doc_num', $row[1])
                         ->where('item_code', '!=', $row[4])
                         ->first();

      
        // If the record exists, update it with the new data
        if ($amendRecord) {
            \Log::info('Found record with matching doc_num but different item_code. Updating...');

            $amendRecord->update([
                'doc_num'            => $row[1],
                'customer'           => $row[2],
                'posting_date'       => $row[3],
                'item_code'          => $row[4],  // Update to new item_code from the Excel file
                'item_name'          => $row[5],
                'quantity'           => $row[6],
                'sales_uom'          => $row[7],
                'packaging_quantity' => $row[8],
                'sales_pack'         => $row[9],
                'create_date'        => $row[10],
                'update_date'        => $row[11],
                'update_fulltime'    => $row[12],
                'is_finish'          => $amendRecord->is_finish,  // Keep is_finish unchanged
                'is_done'            => $amendRecord->is_done,    // Keep is_done unchanged
            ]);

            return $amendRecord;  // Return the updated record
        }

        // // Check if a record with the same doc_num and item_code already exists
        $existingRecord = SoData::where('doc_num', $row[1])
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
                'create_date'        => $row[10],
                'update_date'        => $row[11],
                'update_fulltime'    => $row[12],
                // Leave is_finish and is_done unchanged
            ]);
            
            return $existingRecord;
        }

        // dd($row);

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
            'create_date'        => $row[10],
            'update_date'        => $row[11],
            'update_fulltime'    => $row[12],
        ]);
    }
}
