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
class LA09CreateController extends Controller
{
    // ------------------------------------------------------------------------
    public function index()
    {
        // --------------------------------------------------------------------
        $data = new \stdClass;
        $data->title            = "LA09 - Form";
        $data->siswaBaru        = new SiswaBaru();
        // --------------------------------------------------------------------
        // Init data
        // --------------------------------------------------------------------
        $data->pageType = "create";

        if(Auth::user()->level_id != 1){
            $data->cabangs = Cabang::where('status', 1)->where('user_id', Auth::user()->id)->pluck('nama', 'id');
        }else{
            $data->cabangs = Cabang::where('status', 1)->pluck('nama', 'id');
        }
        // --------------------------------------------------------------------
        return view('backend.import.la09.form', (array) $data);
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    // Check date
    // ------------------------------------------------------------------------
    public function checkDataValidation(Request $request){
        // --------------------------------------------------------------------
        $data = new \stdClass;
        // --------------------------------------------------------------------
        // Use try catch
        // --------------------------------------------------------------------
        try {
            $input  = $request->all();
            $month  = Carbon::parse('01 '.$input['date'])->format('m');
            $year   = Carbon::parse('01 '.$input['date'])->format('Y');
            // ----------------------------------------------------------------
            $siswaBaru = SiswaBaru::where('bulan', $month)->where('tahun', $year)->where('cabang_id', $input['cabang_id'])->first();
            if(empty($siswaBaru)){
                $data->status = true;
                $data->message = "Data masih kosong, pembuatan form bisa dilakukan.";
            }else{
                $data->status = false;
                $data->message = "Data sudah terisi, mohon cek kembali pada sistem!";
            }
            // ----------------------------------------------------------------
            return response()->json($data);
            // ----------------------------------------------------------------
        } catch (\Throwable $th) {
            // ----------------------------------------------------------------
            $data->status = false;
            $data->message = "Data tidak valid!";
            // ----------------------------------------------------------------
            return response()->json($data);
            // ----------------------------------------------------------------
        }
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    public function store(Request $request)
    {
        // --------------------------------------------------------------------
        // Set validation
        // --------------------------------------------------------------------
        Validator::make($request->all(), [
            'bulan_tahun'       => 'required',
            'cabang_id'         => 'required',
            'jumlah'            => 'required|numeric',
        ])->validate();
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Use try catch
        // --------------------------------------------------------------------
        try {
            // ----------------------------------------------------------------
            // Create siswa Baru
            // ----------------------------------------------------------------
            $input = $request->all();
            $siswaBaru = [
                "bulan"     => Carbon::parse('01 '.$input['bulan_tahun'])->format('m'),
                "tahun"     => Carbon::parse('01 '.$input['bulan_tahun'])->format('Y'),
                "jumlah"    => $input['jumlah'],
                "cabang_id" => $input['cabang_id'],
                "user_id"   => Auth::user()->id,
            ];
            // ----------------------------------------------------------------
            $mSiswaBaru = SiswaBaru::create($siswaBaru);
            // ----------------------------------------------------------------
            return redirect()->route('import.la09.index')->with('success', __('label.SUCCESS_CREATE_MESSAGE'));
            // ----------------------------------------------------------------
        } catch (\Throwable $th) {
            return redirect()->route('import.la09.index')->with('success', __('label.FAIL_CREATE_MESSAGE'));
        }
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    public function edit($id)
    {
        // --------------------------------------------------------------------
        $data = new \stdClass;
        $data->title            = "LA09 - Edit Form";
        $data->siswaBaru       = SiswaBaru::find($id);
        // --------------------------------------------------------------------
        // Init data
        // --------------------------------------------------------------------
        $data->pageType = "edit";
        if(Auth::user()->level_id != 1){
            $data->cabangs = Cabang::where('status', 1)->where('user_id', Auth::user()->id)->pluck('nama', 'id');
        }else{
            $data->cabangs = Cabang::where('status', 1)->pluck('nama', 'id');
        }
        // --------------------------------------------------------------------
        return view('backend.import.la09.form', (array) $data);
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    public function update(Request $request, $id)
    {
        // --------------------------------------------------------------------
        // Set validation
        // --------------------------------------------------------------------
        Validator::make($request->all(), [
            'id'                => 'required',
            'bulan_tahun'       => 'required',
            'cabang_id'         => 'required',
            'jumlah'            => 'required|numeric',
        ])->validate();
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Use try catch
        // --------------------------------------------------------------------
        try {
            // ----------------------------------------------------------------
            // Update siswa Baru
            // ----------------------------------------------------------------
            $input = $request->all();
            // ----------------------------------------------------------------
            $siswaBaru = SiswaBaru::find($input['id']);
            $siswaBaru->bulan      = Carbon::parse('01 '.$input['bulan_tahun'])->format('m');
            $siswaBaru->tahun      = Carbon::parse('01 '.$input['bulan_tahun'])->format('Y');
            $siswaBaru->jumlah     = $input['jumlah'];
            $siswaBaru->cabang_id  = $input['cabang_id'];
            $siswaBaru->user_id    = Auth::user()->id;
            $siswaBaru->save();
            // ----------------------------------------------------------------
            return redirect()->route('import.la09.index')->with('success', __('label.SUCCESS_UPDATE_MESSAGE'));
            // ----------------------------------------------------------------
        } catch (\Throwable $th) {
            return redirect()->route('import.la09.index')->with('success', __('label.FAIL_UPDATE_MESSAGE'));
        }
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------
}
// ----------------------------------------------------------------------------