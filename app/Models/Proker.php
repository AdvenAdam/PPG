<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proker extends Model
{
    use HasFactory;
    protected $table = 'proker';
    protected $guarded = [
        'id',
        'updated_at',
        'created_at'
    ];
    protected $casts = [
        'waktu_pelaksanaan' => 'array'
    ];

    public function tim()
    {
        return $this->belongsTo(TimProker::class, 'id_tim');
    }
}
