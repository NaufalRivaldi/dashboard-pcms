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
class LA09Controller extends Controller
{
    // ------------------------------------------------------------------------
    public function index()
    {
        // --------------------------------------------------------------------
        $data = new \stdClass; $filtering = new \stdClass;
        $data->title        = "LA09 - List";
        $data->filtering    = $filtering; 
        // --------------------------------------------------------------------
        // Filtering data
        // --------------------------------------------------------------------
        $filtering->bulan   = $this->monthArray();
        // --------------------------------------------------------------------
        return view('backend.import.la09.index', (array) $data);
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    // JSON function
    // ------------------------------------------------------------------------
    public function json($param){
        // --------------------------------------------------------------------
        // Set switch case
        // --------------------------------------------------------------------
        switch ($param) {
            // ----------------------------------------------------------------
            case 'datatable':
                // ------------------------------------------------------------
                $siswaBarus = SiswaBaru::with('cabang', 'user')->select('siswa_baru.*');
                // ------------------------------------------------------------
                // View owner
                // ------------------------------------------------------------
                if(Auth::user()->level_id == 2){
                    // --------------------------------------------------------
                    $cabangs = [];
                    foreach(Auth::user()->cabangs as $row){ array_push($cabangs, $row->id); }
                    // --------------------------------------------------------
                    $siswaBarus->whereIn('cabang_id', $cabangs);
                    // --------------------------------------------------------
                }
                // ------------------------------------------------------------
                // View user cabang
                // ------------------------------------------------------------
                if(Auth::user()->level_id == 4){
                    $siswaBarus->where('cabang_id', Auth::user()->cabang_id);
                }
                // ------------------------------------------------------------
                $datatable = datatables()->of($siswaBarus)->addIndexColumn();
                // ------------------------------------------------------------
                // Add column
                // ------------------------------------------------------------
                // $datatable = $datatable->addColumn('status', function($row){
                //                     if($row->status == 0) return "Pending";
                //                     else return "Accept";
                //                 });
                // ------------------------------------------------------------
                $datatable = $datatable->addColumn('action', function($row){
                                    $button = '<div class="btn-group" role="group" aria-label="Basic example">';
                                    $button .= '<a href="'.route('import.la09.show', $row->id).'" class="btn btn-sm btn-info"><i class="ti-eye"></i></a>';
                                    // $button .= '<a href="'.route('import.la09.edit', $row->id).'" class="btn btn-sm btn-warning"><i class="ti-settings"></i></a>';
                                    $button .= '<button type="button" data-id="'.$row->id.'" class="btn btn-sm btn-danger btn-delete"><i class="ti-trash"></i></button>';
                                    $button .= '</div>';

                                    return $button;
                                });
                // ------------------------------------------------------------

                // ------------------------------------------------------------
                // Filter column
                // ------------------------------------------------------------
                // $datatable = $datatable->filterColumn('status', function($query,$keyword){
                //                     $value = 0;
                //                     if($keyword == "Accept") $value = 1;
                //                     $query->where('status', $value);
                //                 });
                // ------------------------------------------------------------
                $datatable = $datatable->filterColumn('bulan', function($query, $keyword){
                    $value = array_search($keyword, $this->monthArray());
                    $query->where('bulan', $value);
                });
                // ------------------------------------------------------------
                return $datatable->rawColumns(['action'])->make(true);
                // ------------------------------------------------------------                                    
                break;
            // ----------------------------------------------------------------
            default:
                # code...
                break;
            // ----------------------------------------------------------------
        }
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    public function import()
    {
        // --------------------------------------------------------------------
        $data = new \stdClass;
        $data->title        = "LA09 - Import";
        // --------------------------------------------------------------------
        return view('backend.import.la09.import', (array) $data);
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    public function importStore(Request $request)
    {
        // --------------------------------------------------------------------
        // Set validation
        // --------------------------------------------------------------------
        Validator::make($request->all(), [
            'file_import' => 'required',
        ])->validate();
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Use try catch
        // --------------------------------------------------------------------
        try {
            // ----------------------------------------------------------------
            VWSiswaBaru::truncate(); // Delete all data on import table
            // ----------------------------------------------------------------

            // ----------------------------------------------------------------
            // Check name file
            // ----------------------------------------------------------------
            $file = $request->file('file_import');
            if($this->checkNameFile($file->getClientOriginalName())){
                return redirect()->route('import.la09.import')->with('danger', 'File salah, silahkan masukan file yang sesuai!');
            }
            // ----------------------------------------------------------------

            // ----------------------------------------------------------------
            // Import data & set to database penjualan and penjualan detail
            // ----------------------------------------------------------------
            $import = new LA09Import($file->getClientOriginalName());
            Excel::import($import, $file);

            VWSiswaBaru::create([
                'bulan' => $import->date()[1],
                'tahun' => $import->date()[0],
                'cabang' => $import->kodeCabang(),
                'jumlah' => $import->getRowCount(),
            ]);
            // ----------------------------------------------------------------
            $vwSiswaBarus = VWSiswaBaru::all();
            // ----------------------------------------------------------------
            // Check cabang and user owner if it's exits
            // ----------------------------------------------------------------
            // Admin
            // ----------------------------------------------------------------
            if(Auth::user()->level_id == 1){
                $cabang = Cabang::where('kode', strtoupper($vwSiswaBarus->random()->cabang))->where('status', 1)->first();
            }
            // ----------------------------------------------------------------
            // Owner
            // ----------------------------------------------------------------
            if(Auth::user()->level_id == 2){
                $cabang = Cabang::where('kode', strtoupper($vwSiswaBarus->random()->cabang))->where('status', 1)->where('user_id', Auth::user()->id)->first();
            }
            // ----------------------------------------------------------------
            // User
            // ----------------------------------------------------------------
            if(Auth::user()->level_id == 4){
                $cabang = Cabang::where('kode', strtoupper($vwSiswaBarus->random()->cabang))->where('status', 1)->where('id', Auth::user()->cabang_id)->first();
            }
            // ----------------------------------------------------------------
            if(empty($cabang)){
                return redirect()->route('import.la09.import')->with('danger', __('Cabang tidak sesuai dengan user, silahkan masukkan data dengan cabang yang benar'));
            }
            // ----------------------------------------------------------------
            // Check siswa aktif
            // ----------------------------------------------------------------
            $siswaBaru = SiswaBaru::where('bulan', $vwSiswaBarus->random()->bulan)->where('tahun', $vwSiswaBarus->random()->tahun)->where('cabang_id', $cabang->id)->first();
            // ------------------------------------------------------------
            if(empty($siswaBaru)){
                $siswaBaru = SiswaBaru::create([
                    'bulan'         => $vwSiswaBarus->random()->bulan,
                    'tahun'         => $vwSiswaBarus->random()->tahun,
                    'jumlah'        => $vwSiswaBarus->random()->jumlah,
                    'user_id'       => Auth::user()->id,
                    'cabang_id'     => $cabang->id,
                ]);
            }else{
                $siswaBaru->delete();
                $siswaBaru = SiswaBaru::create([
                    'bulan'         => $vwSiswaBarus->random()->bulan,
                    'tahun'         => $vwSiswaBarus->random()->tahun,
                    'jumlah'        => $vwSiswaBarus->random()->jumlah,
                    'user_id'       => Auth::user()->id,
                    'cabang_id'     => $cabang->id,
                ]);
            }
            // ----------------------------------------------------------------
            
            // ----------------------------------------------------------------
            return redirect()->route('import.la09.index')->with('success', __('label.SUCCESS_CREATE_MESSAGE'));
            // ----------------------------------------------------------------
        } catch (\Throwable $th) {
            return redirect()->route('import.la09.import')->with('danger', 'Format CSV tidak sesuai!');
        }
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    public function destroy($id)
    {
        // --------------------------------------------------------------------
        $data = new \stdClass;
        // --------------------------------------------------------------------
        $siswaBaru = SiswaBaru::findOrFail($id);
        // --------------------------------------------------------------------
        $siswaBaru->delete();
        // --------------------------------------------------------------------
        $data->message = __('label.SUCCESS_DELETE_MESSAGE');
        // --------------------------------------------------------------------
        return response()->json($data);
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    // Set month array
    // ------------------------------------------------------------------------
    private function monthArray(){
        // --------------------------------------------------------------------
        $array = [];
        // --------------------------------------------------------------------
        for($i = 1; $i <= 12; $i++){
            $idx = strlen($i) == 1 ? "0".$i : $i;
            $array[$i] = Carbon::parse('2020-'.$idx.'-01')->format('F');
        }
        // --------------------------------------------------------------------
        return $array;
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    // Check name file function
    // ------------------------------------------------------------------------
    private function checkNameFile($fileName){
        $valArray = explode('-', $fileName);
        // --------------------------------------------------------------------
        if($valArray[1] != "LA09") return true;
        else return false;
    }
    // ------------------------------------------------------------------------
}
// ----------------------------------------------------------------------------