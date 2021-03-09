<?php
// ------------------------------------------------------------------------------------
namespace App\Models;
// ------------------------------------------------------------------------------------
use Illuminate\Database\Eloquent\Model;
// ------------------------------------------------------------------------------------
class Cabang extends Model
{
    // --------------------------------------------------------------------------------
    protected $table = 'cabang';
    // --------------------------------------------------------------------------------
    protected $fillable = ['kode', 'nama', 'status', 'wilayah_id', 'sub_wilayah_id'];
    // --------------------------------------------------------------------------------

    // --------------------------------------------------------------------------------
    // Set relationship
    // --------------------------------------------------------------------------------
    public function wilayah(){
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }
    // --------------------------------------------------------------------------------
    public function sub_wilayah(){
        return $this->belongsTo(SubWilayah::class, 'sub_wilayah_id');
    }
    // --------------------------------------------------------------------------------
}
// ------------------------------------------------------------------------------------
