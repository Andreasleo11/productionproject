<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoData extends Model
{
    use HasFactory;
    protected $table = 'so_datas';
    public $timestamps = false;
    protected $fillable = [
        'is_finish',
        'is_done',
        'updated_at'
    ];

    public function scannedData()
    {
        return $this->hasMany(ScannedData::class, 'doc_num', 'doc_num');
    }
}
