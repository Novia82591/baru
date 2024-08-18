<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruang extends Model
{
    use HasFactory;
    protected $table = 'ruang';
    protected $guarded = [];

    public function tahunajaran()
    {
        return $this->belongsTo('App\Models\TahunAjaran', 'tahun_ajaran_id');
    }
}
