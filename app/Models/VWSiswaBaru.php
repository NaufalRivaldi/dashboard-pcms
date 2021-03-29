<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VWSiswaBaru extends Model
{
    // --------------------------------------------------------------------------------
    protected $table = 'vw_siswa_baru';
    // --------------------------------------------------------------------------------
    protected $fillable = [
        'bulan', 
        'tahun', 
        'cabang',
        'jumlah',
    ];
    // --------------------------------------------------------------------------------
}
