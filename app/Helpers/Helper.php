<?php 
// ----------------------------------------------------------------------------
// Parse JSON
// ----------------------------------------------------------------------------
if (! function_exists('parseJson')) {
    // ------------------------------------------------------------------------
    function parseJson($array){
        return str_replace("'","`", json_encode($array));
    }
    // ------------------------------------------------------------------------
}
// ----------------------------------------------------------------------------

// ----------------------------------------------------------------------------
// Remove underscore "_" and change to space " "
// ----------------------------------------------------------------------------
if (! function_exists('replaceUnderscore')) {
    // ------------------------------------------------------------------------
    function replaceUnderscore($text){
        return str_replace('_', ' ', $text);
    }
    // ------------------------------------------------------------------------
}
// ----------------------------------------------------------------------------

// ----------------------------------------------------------------------------
// Remove min "-" and change to space " "
// ----------------------------------------------------------------------------
if (! function_exists('replaceMin')) {
    // ------------------------------------------------------------------------
    function replaceMin($text){
        return str_replace('-', ' ', $text);
    }
    // ------------------------------------------------------------------------
}
// ----------------------------------------------------------------------------

// ----------------------------------------------------------------------------
// Set button for status
// ----------------------------------------------------------------------------
if (! function_exists('statusButton')) {
    // ------------------------------------------------------------------------
    function statusButton($val, $id){
        if($val == 1){
            return '<button class="btn btn-success btn-status" data-id="'.$id.'" data-type="0">Active</button>';
        }else{
            return '<button class="btn btn-secondary btn-status" data-id="'.$id.'" data-type="1">Inactive</button>';
        }
    }
    // ------------------------------------------------------------------------
}
// ----------------------------------------------------------------------------

// ----------------------------------------------------------------------------
// Set status (active, inactive)
// ----------------------------------------------------------------------------
if (! function_exists('status')) {
    // ------------------------------------------------------------------------
    function status($val){
        if($val == 1){
            return '<span class="badge badge-success">Active</span>';
        }else{
            return '<span class="badge badge-secondary">Inactive</span>';
        }
    }
    // ------------------------------------------------------------------------
}
// ----------------------------------------------------------------------------

// ----------------------------------------------------------------------------
// Set status pembayaran (Accept, Pending)
// ----------------------------------------------------------------------------
if (! function_exists('statusValidate')) {
    // ------------------------------------------------------------------------
    function statusValidate($val){
        if($val == 1){
            return '<span class="badge badge-success">Approve</span>';
        }else{
            return '<span class="badge badge-secondary">Pending</span>';
        }
    }
    // ------------------------------------------------------------------------
}
// ----------------------------------------------------------------------------

// ----------------------------------------------------------------------------
// Set pembayaran type
// ----------------------------------------------------------------------------
if (! function_exists('pembayaranType')) {
    // ------------------------------------------------------------------------
    function pembayaranType($val){
        if($val == 1){
            return '<span class="badge badge-success">Penerimaan Uang Pendaftaran</span>';
        }else{
            return '<span class="badge badge-info">Penerimaan Uang Kursus</span>';
        }
    }
    // ------------------------------------------------------------------------
}
// ----------------------------------------------------------------------------

// ----------------------------------------------------------------------------
// Set month
// ----------------------------------------------------------------------------
if (! function_exists('setMonth')) {
    // ------------------------------------------------------------------------
    function setMonth($val){
        if($val == 1) return "Januari";
        if($val == 2) return "Februari";
        if($val == 3) return "Maret";
        if($val == 4) return "April";
        if($val == 5) return "Mei";
        if($val == 6) return "Juni";
        if($val == 7) return "Juli";
        if($val == 8) return "Agustus";
        if($val == 9) return "September";
        if($val == 10) return "Oktober";
        if($val == 11) return "November";
        if($val == 12) return "Desember";
    }
    // ------------------------------------------------------------------------
}
// ----------------------------------------------------------------------------