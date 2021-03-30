<?php
// ----------------------------------------------------------------------------
namespace App\Http\Controllers\Backend\Import\LA09;
// ----------------------------------------------------------------------------
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Imports\LA09Import;
use Maatwebsite\Excel\Facades\Excel;
// ----------------------------------------------------------------------------
use App\Helpers\ImportHelper;
// ----------------------------------------------------------------------------
use App\Models\VWSiswaBaru; // LA09 - model
use App\Models\SiswaBaru; // LA09 - model
use App\Models\Cabang;
// ----------------------------------------------------------------------------
use Carbon\Carbon;
use Auth;
// ----------------------------------------------------------------------------
class LA09DetailController extends Controller
{
    // ------------------------------------------------------------------------
    public function index($id)
    {
        // --------------------------------------------------------------------
        $data = new \stdClass; $filtering = new \stdClass;
        $data->title        = "LA09 - Detail";
        $data->filtering    = $filtering; 
        $data->siswaBaru    = SiswaBaru::with('user', 'cabang')->where('id', $id)->first();
        // --------------------------------------------------------------------
        return view('backend.import.la09.show', (array) $data);
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------
}
// ----------------------------------------------------------------------------