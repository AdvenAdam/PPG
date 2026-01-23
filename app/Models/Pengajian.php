<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengajian extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'waktu_tanggal_mulai',
        'id_kelompok',
        'materi',
        'tingkat'
    ];

    function Absens(): HasMany
    {
        return $this->hasMany(Absen::class, 'id_pengajian');
    }
    function Kelompok(): BelongsTo
    {
        return $this->belongsTo(Kelompok::class, 'id_kelompok', 'id');
    }
}
