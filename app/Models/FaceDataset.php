<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaceDataset extends Model
{
    use HasFactory;

    protected $fillable = [
        'jamaah_id',
        'image_path'
    ];

    /**
     * Relasi: Foto ini milik satu Jamaah
     */
    public function jamaah()
    {
        return $this->belongsTo(Jamaah::class, 'jamaah_id');
    }
}