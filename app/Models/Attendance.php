<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'jamaah_id',
        'waktu_hadir',
        'tanggal',
        'capture_image_path',
        'confidence_score',
        'lokasi_kamera',
        'status_kehadiran',
        'pengajian_group_id'
    ];

    protected $casts = [
        'waktu_hadir' => 'datetime',
        'tanggal' => 'date',
        'confidence_score' => 'float',
    ];

    /**
     * Relasi: Data absensi ini milik siapa?
     */
    public function jamaah()
    {
        return $this->belongsTo(Jamaah::class, 'jamaah_id');
    }

    public function group()
    {
        return $this->belongsTo(PengajianGroup::class, 'pengajian_group_id');
    }
}