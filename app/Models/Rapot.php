<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rapot extends Model
{
    use HasFactory;

    protected $fillable = [
        'jamaah_id',
        'periode',
        'catatan_wali',
        'nilai',
        'keputusan',
        'created_by_user_id'
    ];

    protected $casts = [
        'nilai' => 'array',
    ];

    public function jamaah()
    {
        return $this->belongsTo(Jamaah::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
