<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionScanData extends Model
{
    use HasFactory;
    protected $table = 'production_scan_data';

    protected $fillable = [
        'daily_item_code_id',
        'item_code',
        'label'
    ];
    
}
