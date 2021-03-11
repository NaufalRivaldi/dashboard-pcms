<?php
// ------------------------------------------------------------------------------------
namespace App\Models;
// ------------------------------------------------------------------------------------
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// ------------------------------------------------------------------------------------
class SubWilayah extends Model
{
    // --------------------------------------------------------------------------------
    use SoftDeletes;
    // --------------------------------------------------------------------------------
    protected $table = 'sub_wilayah';
    // --------------------------------------------------------------------------------
    protected $fillable = ['kode', 'nama', 'status'];
    // --------------------------------------------------------------------------------
    public $timestamps = false;
    // --------------------------------------------------------------------------------

    // --------------------------------------------------------------------------------
    // Set relationship
    // --------------------------------------------------------------------------------
    public function cabangs(){
        return $this->hasMany(Cabang::class, 'sub_wilayah_id');
    }
    // --------------------------------------------------------------------------------
}
// ------------------------------------------------------------------------------------