<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory; 
    protected $table = 'tahunajarans';
    protected $fillable = ['tahun_ajaran','ganjil_genap', 'is_active'];

    public static function getActiveTahunAjaran()
    {
        return self::where('is_active', true)->first();
    }
}
