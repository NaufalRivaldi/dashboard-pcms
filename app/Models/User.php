<?php
// ------------------------------------------------------------------------------------
namespace App\Models;
// ------------------------------------------------------------------------------------
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// ------------------------------------------------------------------------------------
class User extends Authenticatable
{
    // --------------------------------------------------------------------------------
    use Notifiable;
    // --------------------------------------------------------------------------------

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // --------------------------------------------------------------------------------
    protected $table = 'user';
    // --------------------------------------------------------------------------------
    protected $fillable = [
        'nama', 'username', 'email', 'password', 'status', 'level_id', 'cabang_id'
    ];
    // --------------------------------------------------------------------------------

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    // --------------------------------------------------------------------------------
    protected $hidden = [
        'password', 'remember_token',
    ];
    // --------------------------------------------------------------------------------

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    // --------------------------------------------------------------------------------
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    // --------------------------------------------------------------------------------

    // --------------------------------------------------------------------------------
    // Set relationship
    // --------------------------------------------------------------------------------
    public function level(){
        return $this->belongsTo(Level::class, 'level_id');
    }
    // --------------------------------------------------------------------------------
    public function cabangs(){
        return $this->hasMany(Cabang::class, 'user_id');
    }
    // --------------------------------------------------------------------------------
    public function cabang_user(){
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }
    // --------------------------------------------------------------------------------
    public function pembayarans(){
        return $this->hasMany(Pembayaran::class, 'user_id');
    }
    // --------------------------------------------------------------------------------
    public function siswa_aktifs(){
        return $this->hasMany(SiswaAktif::class, 'user_id');
    }
    // --------------------------------------------------------------------------------
    public function siswa_barus(){
        return $this->hasMany(SiswaBaru::class, 'user_id');
    }
    // --------------------------------------------------------------------------------
}
// ------------------------------------------------------------------------------------
