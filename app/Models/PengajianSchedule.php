<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajianSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['pengajian_group_id', 'hari', 'jam_mulai', 'jam_selesai'];

    public function group()
    {
        return $this->belongsTo(PengajianGroup::class, 'pengajian_group_id');
    }
}
