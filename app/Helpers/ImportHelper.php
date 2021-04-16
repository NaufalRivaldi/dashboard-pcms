<?php
// ----------------------------------------------------------------------------
namespace App\Helpers;
// ----------------------------------------------------------------------------
use Illuminate\Support\Facades\DB;
// ----------------------------------------------------------------------------
use App\Models\Cabang;
use App\Models\Summary;
// ----------------------------------------------------------------------------
use Auth;
// ----------------------------------------------------------------------------
class ImportHelper {
    // ------------------------------------------------------------------------
    public static function createCabang($kode) {
        // --------------------------------------------------------------------
        $cabang = Cabang::create([
            'kode' => $kode,
            'nama' => null,
            'wilayah_id' => null,
            'sub_wilayah_id' => null,
            'user_id' => null,
        ]);
        // --------------------------------------------------------------------
        return $cabang;
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    public static function notifSummary() {
        // --------------------------------------------------------------------
        $summary = Summary::where('status', 0);
        // --------------------------------------------------------------------
        // Set level view for list
        // --------------------------------------------------------------------
        // View owner
        // --------------------------------------------------------------------
        if(Auth::user()->level_id == 2){
            // ----------------------------------------------------------------
            $cabangs = [];
            foreach(Auth::user()->cabangs as $row){ array_push($cabangs, $row->id); }
            // ----------------------------------------------------------------
            $summary->whereIn('cabang_id', $cabangs);
            // ----------------------------------------------------------------
        }
        // --------------------------------------------------------------------
        // View user
        // --------------------------------------------------------------------
        if(Auth::user()->level_id == 4){
            $summary->where('cabang_id', Auth::user()->cabang_id);
        }
        // --------------------------------------------------------------------
        return count($summary->get());
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------
}
// ----------------------------------------------------------------------------