<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Desa extends Model
{
    use HasFactory;

    protected $table = 'desa';

    protected $fillable = [
        'id',
        'nama',
        'alamat',
        'id_daerah',
        'created_at',
        'updated_at'
    ];

    function Generus(): HasMany
    {
        return $this->hasMany(Generus::class);
    }
}
