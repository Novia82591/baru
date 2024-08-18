<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;
    
    protected $table = 'jadwal';
    protected $guarded = [];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'kd_ruang', 'kd_ruang');
    }

    public function tahunajaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }
}
