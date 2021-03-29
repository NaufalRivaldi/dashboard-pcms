<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiswaAktifPendidikanDetail extends Model
{
    // --------------------------------------------------------------------------------
    protected $table = 'siswa_aktif_pendidikan_detail';
    // --------------------------------------------------------------------------------
    protected $fillable = [
        'jumlah', 
        'pendidikan_id', 
        'siswa_aktif_pendidikan_id', 
    ];
    // --------------------------------------------------------------------------------
    protected $appends = [
        //
    ];
    // --------------------------------------------------------------------------------

    // --------------------------------------------------------------------------------
    // Set relationship
    // --------------------------------------------------------------------------------
    public function pendidikan(){
        return $this->belongsTo(Pendidikan::class, 'pendidikan_id');
    }
    // --------------------------------------------------------------------------------
    public function siswa_aktif_pendidikan(){
        return $this->belongsTo(SiswaAktifPendidikan::class, 'siswa_aktif_pendidikan_id');
    }
    // --------------------------------------------------------------------------------

    // --------------------------------------------------------------------------------
    // Set Accessor
    // --------------------------------------------------------------------------------
    // public function getBulanTahunAttribute(){
    //     $date = $this->tahun.'/'.$this->bulan.'/01';
    //     return date('F Y', strtotime($date));
    // }
    // --------------------------------------------------------------------------------
}
