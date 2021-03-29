<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VWSiswaInaktif extends Model
{
    // --------------------------------------------------------------------------------
    protected $table = 'vw_siswa_inaktif';
    // --------------------------------------------------------------------------------
    protected $fillable = [
        'bulan', 
        'tahun', 
        'cabang',
        'jumlah',
    ];
    // --------------------------------------------------------------------------------
}
