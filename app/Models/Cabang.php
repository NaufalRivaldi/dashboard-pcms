<?php
// ------------------------------------------------------------------------------------
namespace App\Models;
// ------------------------------------------------------------------------------------
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// ------------------------------------------------------------------------------------
class Cabang extends Model
{
    // --------------------------------------------------------------------------------
    use SoftDeletes;
    // --------------------------------------------------------------------------------
    protected $table = 'cabang';
    // --------------------------------------------------------------------------------
    protected $fillable = ['kode', 'nama', 'latitude', 'longitude', 'status', 'wilayah_id', 'sub_wilayah_id', 'user_id'];
    // --------------------------------------------------------------------------------
    public $timestamps = false;
    // --------------------------------------------------------------------------------

    // --------------------------------------------------------------------------------
    // Set relationship
    // --------------------------------------------------------------------------------
    public function wilayah(){
        return $this->belongsTo(Wilayah::class, 'wilayah_id')->withTrashed();
    }
    // --------------------------------------------------------------------------------
    public function sub_wilayah(){
        return $this->belongsTo(SubWilayah::class, 'sub_wilayah_id')->withTrashed();
    }
    // --------------------------------------------------------------------------------
    public function owner(){
        return $this->belongsTo(User::class, 'user_id');
    }
    // --------------------------------------------------------------------------------
    public function cabang_users(){
        return $this->hasMany(User::class, 'cabang_id');
    }
    // --------------------------------------------------------------------------------
    public function pembayarans(){
        return $this->hasMany(Pembayaran::class, 'cabang_id')->withTrashed();
    }
    // --------------------------------------------------------------------------------
}
// ------------------------------------------------------------------------------------
