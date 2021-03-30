<?php
// ----------------------------------------------------------------------------
namespace App\Http\Controllers\Backend\Import\LA12;
// ----------------------------------------------------------------------------
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Imports\LA12Import;
use Maatwebsite\Excel\Facades\Excel;
// ----------------------------------------------------------------------------
use App\Helpers\ImportHelper;
// ----------------------------------------------------------------------------
use App\Models\VWSiswaInaktif; // LA12 - model
use App\Models\SiswaInaktif; // LA12 - model
use App\Models\Cabang;
// ----------------------------------------------------------------------------
use Carbon\Carbon;
use Auth;
// ----------------------------------------------------------------------------
class LA12DetailController extends Controller
{
    // ------------------------------------------------------------------------
    public function index($id)
    {
        // --------------------------------------------------------------------
        $data = new \stdClass; $filtering = new \stdClass;
        $data->title        = "LA12 - Detail";
        $data->filtering    = $filtering; 
        $data->siswaInaktif = SiswaInaktif::with('user', 'cabang')->where('id', $id)->first();
        // --------------------------------------------------------------------
        return view('backend.import.la12.show', (array) $data);
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------
}
// ----------------------------------------------------------------------------