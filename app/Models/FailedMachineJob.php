<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FailedMachineJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'machine_id',
        'spk_no',
        'target',
        'outstanding',
        'reason',
    ];
}
