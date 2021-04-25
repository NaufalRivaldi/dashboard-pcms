<?php
// ----------------------------------------------------------------------------
namespace App\Http\Controllers\Backend\Import\LA07;
// ----------------------------------------------------------------------------
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Imports\LA07Import;
use Maatwebsite\Excel\Facades\Excel;
// ----------------------------------------------------------------------------
use App\Helpers\ImportHelper;
// ----------------------------------------------------------------------------
use App\Models\VWSiswaAktifPendidikan as VWSiswaAktif; // LA07 - model
use App\Models\SiswaAktifPendidikan as SiswaAktif; // LA07 - model
use App\Models\SiswaAktifPendidikanDetail as SiswaAktifDetail; // LA07 - model
use App\Models\Cabang;
use App\Models\Pendidikan;
// ----------------------------------------------------------------------------
use Carbon\Carbon;
use Auth;
// ----------------------------------------------------------------------------
class LA07Controller extends Controller
{
    // ------------------------------------------------------------------------
    public function index()
    {
        // --------------------------------------------------------------------
        $data = new \stdClass; $filtering = new \stdClass;
        $data->title        = "LA07 - List";
        $data->filtering    = $filtering; 
        // --------------------------------------------------------------------
        // Filtering data
        // --------------------------------------------------------------------
        $filtering->bulan   = $this->monthArray();
        // --------------------------------------------------------------------
        return view('backend.import.la07.index', (array) $data);
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
                $siswaAktifs = SiswaAktif::with('cabang', 'user')->select('siswa_aktif_pendidikan.*');
                // ------------------------------------------------------------
                // View owner
                // ------------------------------------------------------------
                if(Auth::user()->level_id == 2){
                    // --------------------------------------------------------
                    $cabangs = [];
                    foreach(Auth::user()->cabangs as $row){ array_push($cabangs, $row->id); }
                    // --------------------------------------------------------
                    $siswaAktifs->whereIn('cabang_id', $cabangs);
                    // --------------------------------------------------------
                }
                // ------------------------------------------------------------
                // View user cabang
                // ------------------------------------------------------------
                if(Auth::user()->level_id == 4){
                    $siswaAktifs->where('cabang_id', Auth::user()->cabang_id);
                }
                // ------------------------------------------------------------
                $datatable = datatables()->of($siswaAktifs)->addIndexColumn();
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
                                    $button .= '<a href="'.route('import.la07.show', $row->id).'" class="btn btn-sm btn-info"><i class="ti-eye"></i></a>';
                                    // $button .= '<a href="'.route('import.la07.edit', $row->id).'" class="btn btn-sm btn-warning"><i class="ti-settings"></i></a>';
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
        $data->title        = "LA07 - Import";
        // --------------------------------------------------------------------
        return view('backend.import.la07.import', (array) $data);
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
            VWSiswaAktif::truncate(); // Delete all data on import table
            // ----------------------------------------------------------------

            // ----------------------------------------------------------------
            // Check name file
            // ----------------------------------------------------------------
            $file = $request->file('file_import');
            if($this->checkNameFile($file->getClientOriginalName())){
                return redirect()->route('import.la07.import')->with('danger', 'File salah, silahkan masukan file yang sesuai!');
            }
            // ----------------------------------------------------------------

            // ----------------------------------------------------------------
            // Import data & set to database penjualan and penjualan detail
            // ----------------------------------------------------------------
            Excel::import(new LA07Import($file->getClientOriginalName()), $file);
            // ----------------------------------------------------------------
            $vwSiswaAktifs = VWSiswaAktif::all();
            // ----------------------------------------------------------------
            // Check cabang and user owner if it's exits
            // ----------------------------------------------------------------
            // Admin
            // ----------------------------------------------------------------
            if(Auth::user()->level_id == 1){
                $cabang = Cabang::where('kode', strtoupper($vwSiswaAktifs->random()->cabang))->where('status', 1)->first();
            }
            // ----------------------------------------------------------------
            // Owner
            // ----------------------------------------------------------------
            if(Auth::user()->level_id == 2){
                $cabang = Cabang::where('kode', strtoupper($vwSiswaAktifs->random()->cabang))->where('status', 1)->where('user_id', Auth::user()->id)->first();
            }
            // ----------------------------------------------------------------
            // User
            // ----------------------------------------------------------------
            if(Auth::user()->level_id == 4){
                $cabang = Cabang::where('kode', strtoupper($vwSiswaAktifs->random()->cabang))->where('status', 1)->where('id', Auth::user()->cabang_id)->first();
            }
            // ----------------------------------------------------------------
            if(empty($cabang)){
                return redirect()->route('import.la07.import')->with('danger', __('Cabang tidak sesuai dengan user, silahkan masukkan data dengan cabang yang benar'));
            }
            // ----------------------------------------------------------------
            // Check siswa aktif
            // ----------------------------------------------------------------
            $siswaAktif = SiswaAktif::where('bulan', $vwSiswaAktifs->random()->bulan)->where('tahun', $vwSiswaAktifs->random()->tahun)->where('cabang_id', $cabang->id)->first();
            // ------------------------------------------------------------
            if(empty($siswaAktif)){
                $siswaAktif = SiswaAktif::create([
                    'bulan'         => $vwSiswaAktifs->random()->bulan,
                    'tahun'         => $vwSiswaAktifs->random()->tahun,
                    'user_id'       => Auth::user()->id,
                    'cabang_id'     => $cabang->id,
                ]);
            }else{
                SiswaAktifDetail::where('siswa_aktif_pendidikan_id', $siswaAktif->id)->delete();
            }
            // ------------------------------------------------------------
            // Insert data siswa aktif detail
            // ------------------------------------------------------------
            foreach($vwSiswaAktifs as $vwSiswaAktif){
                $pendidikan = Pendidikan::where('nama', $vwSiswaAktif->pendidikan)->first();
                if(empty($pendidikan)){
                    $pendidikan = Pendidikan::create([
                        'nama' => $vwSiswaAktif->pendidikan,
                        'status' => 1,
                    ]);
                }
                // --------------------------------------------------------
                SiswaAktifDetail::create([
                    'jumlah'                    => $vwSiswaAktif->jumlah,
                    'pendidikan_id'             => $pendidikan->id,
                    'siswa_aktif_pendidikan_id' => $siswaAktif->id,
                ]);
            }
            // ------------------------------------------------------------
            
            // ----------------------------------------------------------------
            return redirect()->route('import.la07.index')->with('success', __('label.SUCCESS_CREATE_MESSAGE'));
            // ----------------------------------------------------------------
        } catch (\Throwable $th) {
            return redirect()->route('import.la07.import')->with('danger', 'Format CSV tidak sesuai!');
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
        $siswaAktif = SiswaAktif::findOrFail($id);
        // --------------------------------------------------------------------
        $siswaAktif->delete();
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
        if($valArray[1] != "LA07") return true;
        else return false;
    }
    // ------------------------------------------------------------------------
}
// ----------------------------------------------------------------------------