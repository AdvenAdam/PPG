<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Musyawarah extends Model
{
    use HasFactory;
    protected $table = 'musyawarah';
    protected $guraded = [
        'id',
    ];
}
