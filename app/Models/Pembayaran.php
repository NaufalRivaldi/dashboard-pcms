<?php
// ------------------------------------------------------------------------------------
namespace App\Models;
// ------------------------------------------------------------------------------------
use Illuminate\Database\Eloquent\Model;
// ------------------------------------------------------------------------------------
class Pembayaran extends Model
{
    // --------------------------------------------------------------------------------
    protected $table = 'pembayaran';
    // --------------------------------------------------------------------------------
    protected $fillable = [
        'bulan', 
        'tahun', 
        'user_id',
        'cabang_id',
    ];
    // --------------------------------------------------------------------------------
    protected $appends = [
        'bulan_tahun', 'u_pendaftaran', 'u_kursus'
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
    public function pembayaran_details(){
        return $this->hasMany(PembayaranDetail::class, 'pembayaran_id');
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
    public function getUPendaftaranAttribute(){
        $nominal = 0;
        foreach($this->pembayaran_details as $row){
            if($row->type == 1) $nominal += $row->nominal;
        }

        return $nominal;
    }
    // --------------------------------------------------------------------------------
    public function getUKursusAttribute(){
        $nominal = 0;
        foreach($this->pembayaran_details as $row){
            if($row->type == 2) $nominal += $row->nominal;
        }

        return $nominal;
    }
    // --------------------------------------------------------------------------------
}
// ------------------------------------------------------------------------------------