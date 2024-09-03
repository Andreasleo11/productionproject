<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyItemCode extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'item_code', 
        'quantity',
        'final_quantity',
        'loss_package_quantity',
        'actual_quantity',
        'is_done'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function spkRelation()
    {
        return $this->hasMany(SpkMaster::class);
    }
}
