<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'id',
        'nama',
        'created_at',
        'updated_at',
    ];
    function Generus(): HasMany
    {
        return $this->hasMany(Generus::class);
    }
}
