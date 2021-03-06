<?php
// ------------------------------------------------------------------------------------
namespace App\Models;
// ------------------------------------------------------------------------------------
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// ------------------------------------------------------------------------------------
class Kategori extends Model
{
    // --------------------------------------------------------------------------------
    use SoftDeletes;
    // --------------------------------------------------------------------------------
    protected $table = 'kategori';
    // --------------------------------------------------------------------------------
    protected $fillable = ['kode', 'nama', 'status'];
    // --------------------------------------------------------------------------------
    public $timestamps = false;
    // --------------------------------------------------------------------------------

    // --------------------------------------------------------------------------------
    // Set relationship
    // --------------------------------------------------------------------------------
    public function materis(){
        return $this->hasMany(Materi::class, 'kategori_id')->withTrashed();
    }
    // --------------------------------------------------------------------------------
}
// ------------------------------------------------------------------------------------