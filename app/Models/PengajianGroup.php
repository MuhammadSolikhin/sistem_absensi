<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajianGroup extends Model
{
    use HasFactory;

    protected $fillable = ['nama_group', 'deskripsi', 'has_rapot'];

    public function schedules()
    {
        return $this->hasMany(PengajianSchedule::class);
    }

    public function jamaah()
    {
        return $this->hasMany(Jamaah::class);
    }
}
