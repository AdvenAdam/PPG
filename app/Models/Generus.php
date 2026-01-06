<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Generus extends Model
{
    use HasFactory;
    protected $table = 'generus';
    protected $fillable = [
        'id',
        'nama',
        'tgllahir',
        'gender',
        'id_desa',
        'id_kelompok',
        'id_kelas',
        'pendidikan_terakhir',
        'status_pekerjaan',
        'detail_pekerjaan',
        'nama_ibu',
        'hum_ibu',
        'nama_bapak',
        'hum_bapak',
        'status',
        'keterangan',
        'mubalight',
        'generus_id',
        'generated_qr',
        'foto_url',
        'created_at',
        'updated_at'
    ];

    public function Desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }
    public function Kelompok(): BelongsTo
    {
        return $this->belongsTo(Kelompok::class);
    }

    public function Kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    protected static function booted()
    {
        static::creating(function ($generus) {
            $lastGenerus = Generus::where('id_kelompok', $generus->id_kelompok)
                ->orderByDesc('generus_id')
                ->first();

            $counter = 1;

            if ($lastGenerus) {
                $lastGenerusId = $lastGenerus->generus_id;
                $counterPart = substr($lastGenerusId, -3);
                $counter = intval($counterPart) + 1;
            }

            $generus->generus_id = $generus->id_desa
                . sprintf("%02d", $generus->id_kelompok)
                . sprintf("%02d", $generus->id_kelas)
                . sprintf("%03d", $counter);
        });
    }
}
