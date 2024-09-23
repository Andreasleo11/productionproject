<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionScannedData extends Model
{
    use HasFactory;

    protected $table = 'production_scanned_data';

    protected $fillable = [
        'spk_code',
        'item_code',
        'warehouse',
        'quantity',
        'label',
    ];
}
