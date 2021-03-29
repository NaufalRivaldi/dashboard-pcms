<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VWSiswaAktifPendidikan extends Model
{
    // --------------------------------------------------------------------------------
    protected $table = 'vw_siswa_aktif_pendidikan';
    // --------------------------------------------------------------------------------
    protected $fillable = [
        'bulan', 
        'tahun', 
        'cabang',
        'pendidikan',
        'jumlah',
    ];
    // --------------------------------------------------------------------------------
}
