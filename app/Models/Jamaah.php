<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jamaah extends Model
{
    use HasFactory;

    protected $table = 'jamaah';

    protected $fillable = [
        'nik',
        'nama_lengkap',
        'jenis_kelamin',
        'no_hp',
        'alamat',
        'status_aktif'
    ];

    /**
     * Relasi: Satu Jamaah memiliki banyak Foto Dataset (untuk Training)
     */
    public function datasets()
    {
        return $this->hasMany(FaceDataset::class, 'jamaah_id');
    }

    /**
     * Relasi: Satu Jamaah memiliki banyak riwayat Absensi
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'jamaah_id');
    }

    // --- SCOPES (Query Shortcut) ---
    
    /**
     * Filter hanya jamaah yang aktif
     * Cara pakai: Jamaah::active()->get();
     */
    public function scopeActive($query)
    {
        return $query->where('status_aktif', true);
    }
}