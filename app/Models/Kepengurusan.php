<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kepengurusan extends Model
{
    use HasFactory;

    protected $table = 'kepengurusan';

    protected $fillable = [
        'id',
        'desa_id',
        'kelompok_id',
        'daerah_id',
        'nama',
        'no_hp',
        'jabatan_id',
        'created_at',
        'updated_at'
    ];

    function Jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class);
    }

    function Desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    function Kelompok(): BelongsTo
    {
        return $this->belongsTo(Kelompok::class);
    }

    function Daerah(): BelongsTo
    {
        return $this->belongsTo(Daerah::class);
    }
}
