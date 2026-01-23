<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelompok extends Model
{
    use HasFactory;

    protected $table = 'kelompok';

    protected $fillable = [
        'id',
        'nama',
        'alamat',
        'id_desa',
        'created_at',
        'updated_at'
    ];

    function Generus(): HasMany
    {
        return $this->hasMany(Generus::class);
    }

    function Desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    function Pengajian(): HasMany
    {
        return $this->hasMany(Pengajian::class);
    }
}
