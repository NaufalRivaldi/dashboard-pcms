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