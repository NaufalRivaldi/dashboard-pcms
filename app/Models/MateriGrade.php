<?php
// ------------------------------------------------------------------------------------
namespace App\Models;
// ------------------------------------------------------------------------------------
use Illuminate\Database\Eloquent\Model;
// ------------------------------------------------------------------------------------
class MateriGrade extends Model
{
    // --------------------------------------------------------------------------------
    protected $table = 'materi_grade';
    // --------------------------------------------------------------------------------
    protected $fillable = ['kode_materi', 'kode_grade', 'biaya', 'materi_id', 'grade_id'];
    // --------------------------------------------------------------------------------
    public $timestamps = false;
    // --------------------------------------------------------------------------------

    // --------------------------------------------------------------------------------
    // Set relationship
    // --------------------------------------------------------------------------------
    public function grade(){
        return $this->belongsTo(Grade::class, 'grade_id')->withTrashed();
    }
    // --------------------------------------------------------------------------------
    public function materi(){
        return $this->belongsTo(Materi::class, 'materi_id')->withTrashed();
    }
    // --------------------------------------------------------------------------------
}
// ------------------------------------------------------------------------------------