<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';
    protected $guarded = [];

    public function matkul()
    {
        return $this->belongsTo(Matkul::class, 'kd_matkul', 'kd_matkul');
    }

    public function dosen1()
    {
        return $this->belongsTo(Dosen::class, 'nidn1', 'nidn');
    }

    public function dosen2()
    {
        return $this->belongsTo(Dosen::class, 'nidn2', 'nidn');
    }

    public function dosen3()
    {
        return $this->belongsTo(Dosen::class, 'nidn3', 'nidn');
    }

    public function dosen4()
    {
        return $this->belongsTo(Dosen::class, 'nidn4', 'nidn');
    }

    public function tahunajaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }
}
