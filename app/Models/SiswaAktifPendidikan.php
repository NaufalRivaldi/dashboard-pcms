<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiswaAktifPendidikan extends Model
{
    // --------------------------------------------------------------------------------
    protected $table = 'siswa_aktif_pendidikan';
    // --------------------------------------------------------------------------------
    protected $fillable = [
        'bulan', 
        'tahun', 
        'user_id',
        'cabang_id',
    ];
    // --------------------------------------------------------------------------------
    protected $appends = [
        'bulan_tahun', 'jumlah_siswa'
    ];
    // --------------------------------------------------------------------------------

    // --------------------------------------------------------------------------------
    // Set relationship
    // --------------------------------------------------------------------------------
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    // --------------------------------------------------------------------------------
    public function cabang(){
        return $this->belongsTo(Cabang::class, 'cabang_id')->withTrashed();
    }
    // --------------------------------------------------------------------------------
    public function siswa_aktif_pendidikan_details(){
        return $this->hasMany(SiswaAktifPendidikanDetail::class, 'siswa_aktif_pendidikan_id');
    }
    // --------------------------------------------------------------------------------

    // --------------------------------------------------------------------------------
    // Set Accessor
    // --------------------------------------------------------------------------------
    public function getBulanTahunAttribute(){
        $date = $this->tahun.'/'.$this->bulan.'/01';
        return date('F Y', strtotime($date));
    }
    // --------------------------------------------------------------------------------
    public function getJumlahSiswaAttribute(){
        return $this->siswa_aktif_pendidikan_details->sum('jumlah');
    }
    // --------------------------------------------------------------------------------
}
