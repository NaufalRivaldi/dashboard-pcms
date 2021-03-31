<?php
// ------------------------------------------------------------------------------------
namespace App\Models;
// ------------------------------------------------------------------------------------
use Illuminate\Database\Eloquent\Model;
// ------------------------------------------------------------------------------------
class Summary extends Model
{
    // --------------------------------------------------------------------------------
    protected $table = 'summary';
    // --------------------------------------------------------------------------------
    protected $guarded = [];
    // --------------------------------------------------------------------------------
    protected $appends = [
        'bulan_tahun'
    ];
    // --------------------------------------------------------------------------------

    // --------------------------------------------------------------------------------
    // Set relationship
    // --------------------------------------------------------------------------------
    public function summary_sa_materi(){
        return $this->hasMany(SummarySAMateri::class, 'summary_id');
    }
    // --------------------------------------------------------------------------------
    public function summary_sa_pendidikan(){
        return $this->hasMany(SummarySAPendidikan::class, 'summary_id');
    }
    // --------------------------------------------------------------------------------
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    // --------------------------------------------------------------------------------
    public function user_approve(){
        return $this->belongsTo(User::class, 'user_approve_id');
    }
    // --------------------------------------------------------------------------------
    public function cabang(){
        return $this->belongsTo(Cabang::class, 'cabang_id')->withTrashed();
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
}
// ------------------------------------------------------------------------------------