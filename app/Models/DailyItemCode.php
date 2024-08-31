<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyItemCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'machine_name',
        'item_code'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
