<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'machine_name',
        'name',
        'mime_type',
        'size'
    ];

    public function document()
    {
        $this->belongsTo(User::class, 'machine_name', 'name');
    }
}
