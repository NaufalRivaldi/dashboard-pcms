<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VWSiswaCuti extends Model
{
    // --------------------------------------------------------------------------------
    protected $table = 'vw_siswa_cuti';
    // --------------------------------------------------------------------------------
    protected $fillable = [
        'bulan',  
        'tahun',  
        'cabang',
        'jumlah',
    ];
    // --------------------------------------------------------------------------------
}
