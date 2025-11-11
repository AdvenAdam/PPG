<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimProker extends Model
{
    use HasFactory;
    protected $table = 'tim_proker';
    protected $guarded = [
        'id',
        'updated_at',
        'created_at'
    ];
    protected $casts = [
        'anggota' => 'array'
    ];

    public function prokers()
    {
        return $this->hasMany(Proker::class, 'id_tim');
    }
}
