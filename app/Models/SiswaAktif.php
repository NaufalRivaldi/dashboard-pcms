<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiswaAktif extends Model
{
    // --------------------------------------------------------------------------------
    protected $table = 'siswa_aktif';
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
    public function siswa_aktif_details(){
        return $this->hasMany(SiswaAktifDetail::class, 'siswa_aktif_id');
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
        return $this->siswa_aktif_details->sum('jumlah');
    }
    // --------------------------------------------------------------------------------
}
