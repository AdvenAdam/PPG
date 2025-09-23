<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sarpras extends Model
{
    use HasFactory;

    protected $table = 'sarpras';

    protected $fillable = [
        'id_kelompok',
        'nama',
        'jumlah',
        'kondisi',
        'keterangan',
    ];

    protected $casts = [
        'kondisi' => 'array',
    ];

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'id_kelompok');
    }
}
