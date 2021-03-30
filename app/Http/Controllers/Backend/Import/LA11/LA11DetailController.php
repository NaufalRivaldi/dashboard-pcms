<?php
// ----------------------------------------------------------------------------
namespace App\Http\Controllers\Backend\Import\LA11;
// ----------------------------------------------------------------------------
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Imports\LA11Import;
use Maatwebsite\Excel\Facades\Excel;
// ----------------------------------------------------------------------------
use App\Helpers\ImportHelper;
// ----------------------------------------------------------------------------
use App\Models\VWSiswaCuti; // LA11 - model
use App\Models\SiswaCuti; // LA11 - model
use App\Models\Cabang;
// ----------------------------------------------------------------------------
use Carbon\Carbon;
use Auth;
// ----------------------------------------------------------------------------
class LA11DetailController extends Controller
{
    // ------------------------------------------------------------------------
    public function index($id)
    {
        // --------------------------------------------------------------------
        $data = new \stdClass; $filtering = new \stdClass;
        $data->title        = "LA13 - Detail";
        $data->filtering    = $filtering; 
        $data->siswaCuti    = SiswaCuti::with('user', 'cabang')->where('id', $id)->first();
        // --------------------------------------------------------------------
        return view('backend.import.la11.show', (array) $data);
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------
}
// ----------------------------------------------------------------------------