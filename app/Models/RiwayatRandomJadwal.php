<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatRandomJadwal extends Model
{
    use HasFactory;

    protected $table = 'riwayat_random_jadwal';
    protected $guarded = [];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'kd_ruang', 'kd_ruang');
    }
}


