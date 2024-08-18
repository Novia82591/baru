<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;
    protected $table = 'mahasiswa';
    protected $guarded = [];

    public function tahunajaran()
    {
        return $this->belongsTo('App\Models\TahunAjaran', 'tahun_ajaran_id');
    }
}
