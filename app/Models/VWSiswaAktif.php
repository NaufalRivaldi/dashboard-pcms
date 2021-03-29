<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VWSiswaAktif extends Model
{
    // --------------------------------------------------------------------------------
    protected $table = 'vw_siswa_aktif';
    // --------------------------------------------------------------------------------
    protected $fillable = [
        'bulan', 
        'tahun', 
        'cabang',
        'materi',
        'jumlah',
    ];
    // --------------------------------------------------------------------------------
}
