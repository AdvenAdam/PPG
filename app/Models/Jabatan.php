<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'jabatan';

    protected $fillable = [
        'id',
        'nama',
        'tingkat',
        'created_at',
        'updated_at'
    ];

    function Kepengurusan(): HasMany
    {
        return $this->hasMany(Kepengurusan::class);
    }
}
