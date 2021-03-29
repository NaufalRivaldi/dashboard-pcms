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
class LA11Controller extends Controller
{
    // ------------------------------------------------------------------------
    public function index()
    {
        // --------------------------------------------------------------------
        $data = new \stdClass; $filtering = new \stdClass;
        $data->title        = "LA11 - List";
        $data->filtering    = $filtering; 
        // --------------------------------------------------------------------
        // Filtering data
        // --------------------------------------------------------------------
        $filtering->bulan   = $this->monthArray();
        $filtering->status  = ["Pending", "Accept"];
        // --------------------------------------------------------------------
        return view('backend.import.la11.index', (array) $data);
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
                $siswaCutis = SiswaCuti::with('cabang', 'user');
                // ------------------------------------------------------------
                $datatable = datatables()->of($siswaCutis)->addIndexColumn();
                // ------------------------------------------------------------
                // Add column
                // ------------------------------------------------------------
                $datatable = $datatable->addColumn('status', function($row){
                                    if($row->status == 0) return "Pending";
                                    else return "Accept";
                                });
                // ------------------------------------------------------------
                $datatable = $datatable->addColumn('action', function($row){
                                    $button = '<div class="btn-group" role="group" aria-label="Basic example">';
                                    $button .= '<a href="'.route('import.la11.show', $row->id).'" class="btn btn-sm btn-info"><i class="ti-eye"></i></a>';
                                    $button .= '<a href="'.route('import.la11.edit', $row->id).'" class="btn btn-sm btn-warning"><i class="ti-settings"></i></a>';
                                    $button .= '<button type="button" data-id="'.$row->id.'" class="btn btn-sm btn-danger btn-delete"><i class="ti-trash"></i></button>';
                                    $button .= '</div>';

                                    return $button;
                                });
                // ------------------------------------------------------------

                // ------------------------------------------------------------
                // Filter column
                // ------------------------------------------------------------
                $datatable = $datatable->filterColumn('status', function($query,$keyword){
                                    $value = 0;
                                    if($keyword == "Accept") $value = 1;
                                    $query->where('status', $value);
                                });
                // ------------------------------------------------------------
                $datatable = $datatable->filterColumn('bulan', function($query, $keyword){
                    $value = array_search($keyword, $this->monthArray());
                    $query->where('bulan', $value);
                });
                // ------------------------------------------------------------
                return $datatable->rawColumns(['status', 'action'])->make(true);
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
        $data->title        = "LA11 - Import";
        // --------------------------------------------------------------------
        return view('backend.import.la11.import', (array) $data);
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
            VWSiswaCuti::truncate(); // Delete all data on import table
            // ----------------------------------------------------------------

            // ----------------------------------------------------------------
            // Check name file
            // ----------------------------------------------------------------
            $file = $request->file('file_import');
            if($this->checkNameFile($file->getClientOriginalName())){
                return redirect()->route('import.la11.import')->with('danger', 'File salah, silahkan masukan file yang sesuai!');
            }
            // ----------------------------------------------------------------

            // ----------------------------------------------------------------
            // Import data & set to database penjualan and penjualan detail
            // ----------------------------------------------------------------
            $import = new LA11Import($file->getClientOriginalName());
            Excel::import($import, $file);

            VWSiswaCuti::create([
                'bulan' => $import->date()[1],
                'tahun' => $import->date()[0],
                'cabang' => $import->kodeCabang(),
                'jumlah' => $import->getRowCount(),
            ]);
            // ----------------------------------------------------------------
            $vwSiswaCutis = VWSiswaCuti::all();
            // ----------------------------------------------------------------
            // Check cabang and user owner if it's exits
            // ----------------------------------------------------------------
            if(Auth::user()->level_id != 1){
                $cabang = Cabang::where('kode', strtoupper($vwSiswaCutis->random()->cabang))->where('status', 1)->where('user_id', Auth::user()->id)->first();
            }else{
                $cabang = Cabang::where('kode', strtoupper($vwSiswaCutis->random()->cabang))->where('status', 1)->first();
            }
            // ----------------------------------------------------------------
            if(empty($cabang)){
                return redirect()->route('import.la11.index')->with('danger', __('Cabang tidak sesuai dengan user owner, silahkan masukkan data yang benar'));
            }
            // ----------------------------------------------------------------
            // Check siswa aktif
            // ----------------------------------------------------------------
            $siswaCuti = SiswaCuti::where('bulan', $vwSiswaCutis->random()->bulan)->where('tahun', $vwSiswaCutis->random()->tahun)->where('cabang_id', $cabang->id)->where('status', 1)->first();
            if(empty($siswaCuti)){
                // ------------------------------------------------------------
                $siswaCuti = SiswaCuti::where('bulan', $vwSiswaCutis->random()->bulan)->where('tahun', $vwSiswaCutis->random()->tahun)->where('cabang_id', $cabang->id)->where('status', 0)->first();
                // ------------------------------------------------------------
                if(empty($siswaCuti)){
                    $siswaCuti = SiswaCuti::create([
                        'bulan'         => $vwSiswaCutis->random()->bulan,
                        'tahun'         => $vwSiswaCutis->random()->tahun,
                        'status'        => 0,
                        'jumlah'        => $vwSiswaCutis->random()->jumlah,
                        'user_id'       => Auth::user()->id,
                        'cabang_id'     => $cabang->id,
                    ]);
                }else{
                    $siswaCuti->delete();
                    $siswaCuti = SiswaCuti::create([
                        'bulan'         => $vwSiswaCutis->random()->bulan,
                        'tahun'         => $vwSiswaCutis->random()->tahun,
                        'status'        => 0,
                        'jumlah'        => $vwSiswaCutis->random()->jumlah,
                        'user_id'       => Auth::user()->id,
                        'cabang_id'     => $cabang->id,
                    ]);
                }
            }else{
                return redirect()->route('import.la11.index')->with('info', 'Data sudah ada dan sudah di approve');
            }
            // ----------------------------------------------------------------
            
            // ----------------------------------------------------------------
            return redirect()->route('import.la11.index')->with('success', __('label.SUCCESS_CREATE_MESSAGE'));
            // ----------------------------------------------------------------
        } catch (\Throwable $th) {
            return redirect()->route('import.la11.index')->with('danger', __('label.FAIL_CREATE_MESSAGE'));
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
        $siswaCuti = SiswaCuti::findOrFail($id);
        // --------------------------------------------------------------------
        $siswaCuti->delete();
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
        if($valArray[1] != "LA11") return true;
        else return false;
    }
    // ------------------------------------------------------------------------
}
// ----------------------------------------------------------------------------