<?php
// ----------------------------------------------------------------------------
namespace App\Helpers;
// ----------------------------------------------------------------------------
use Illuminate\Support\Facades\DB;
// ----------------------------------------------------------------------------
use App\Models\Cabang;
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
}
// ----------------------------------------------------------------------------