<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Absen extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $fillable = [
        'absen',
        'keterangan',
        'id_kelas',
        'id_pengajian',
    ];

    function Pengajian(): BelongsTo
    {
        return $this->belongsTo(Pengajian::class);
    }

    function Kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, "id_kelas");
    }
}
