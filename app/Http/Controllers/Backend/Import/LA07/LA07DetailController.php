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
use App\Models\SiswaAktif as SiswaAktifJurusan; // LA06 - model
use App\Models\SiswaAktifPendidikan as SiswaAktif; // LA07 - model
use App\Models\SiswaAktifPendidikanDetail as SiswaAktifDetail; // LA07 - model
use App\Models\Cabang;
use App\Models\Pendidikan;
// ----------------------------------------------------------------------------
use Carbon\Carbon;
use Auth;
// ----------------------------------------------------------------------------
class LA07DetailController extends Controller
{
    // ------------------------------------------------------------------------
    public function index($id)
    {
        // --------------------------------------------------------------------
        $data = new \stdClass; $filtering = new \stdClass;
        $data->title        = "LA07 - Detail";
        $data->filtering    = $filtering; 
        $data->siswaAktif   = SiswaAktif::with('siswa_aktif_pendidikan_details')->where('id', $id)->first();
        $data->siswaAktifJurusan = SiswaAktifJurusan::with('siswa_aktif_details')->where('bulan', $data->siswaAktif->bulan)->where('tahun', $data->siswaAktif->tahun)->where('cabang_id', $data->siswaAktif->cabang_id)->first();
        $data->pendidikans  = Pendidikan::all();
        // --------------------------------------------------------------------
        // Filtering data
        // --------------------------------------------------------------------
        $filtering->bulan   = $this->monthArray();
        // --------------------------------------------------------------------
        return view('backend.import.la07.show', (array) $data);
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    // JSON function
    // ------------------------------------------------------------------------
    public function json($id, $param){
        // --------------------------------------------------------------------
        // Set switch case
        // --------------------------------------------------------------------
        switch ($param) {
            // ----------------------------------------------------------------
            case 'datatable':
                // ------------------------------------------------------------
                $siswaAktifDetails = SiswaAktifDetail::with('pendidikan')->select('siswa_aktif_pendidikan_detail.*')->where('siswa_aktif_pendidikan_id', $id);
                // ------------------------------------------------------------
                $datatable = datatables()->of($siswaAktifDetails)->addIndexColumn();
                // ------------------------------------------------------------
                // Add column
                // ------------------------------------------------------------
                $datatable = $datatable->addColumn('action', function($row){
                                    $button = '<div class="btn-group" role="group" aria-label="Basic example">';
                                    $button .= '<button class="btn btn-sm btn-warning btn-edit" data-id="'.$row->id.'" data-toggle="modal" data-target="#modalEdit"><i class="ti-settings"></i></button>';
                                    $button .= '<button type="button" data-id="'.$row->id.'" class="btn btn-sm btn-danger btn-delete"><i class="ti-trash"></i></button>';
                                    $button .= '</div>';

                                    return $button;
                                });
                // ------------------------------------------------------------

                // ------------------------------------------------------------
                // Filter column
                // ------------------------------------------------------------
                // $datatable = $datatable->filterColumn('type', function($query,$keyword){
                //                     $value = 1;
                //                     if($keyword == "Penerimaan Uang Kursus") $value = 2;
                //                     $query->where('type', $value);
                //                 });
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
    public function update(Request $request, $id)
    {
        
        // --------------------------------------------------------------------
        // Set validation
        // --------------------------------------------------------------------
        Validator::make($request->all(), [
            'id'            => 'required',
            'pendidikan_id' => 'required',
            'jumlah'        => 'numeric',
        ])->validate();
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Use try catch
        // --------------------------------------------------------------------
        try {
            // ----------------------------------------------------------------
            $data = $request->all();
            // ----------------------------------------------------------------
            $siswaAktifDetail = SiswaAktifDetail::findOrFail($data['id']);
            $siswaAktifDetail->pendidikan_id = $data['pendidikan_id'];
            $siswaAktifDetail->jumlah = $data['jumlah'];
            $siswaAktifDetail->save();
            // ----------------------------------------------------------------
            return redirect()->route('import.la07.show', $id)->with('success', __('label.SUCCESS_UPDATE_MESSAGE'));
            // ----------------------------------------------------------------
        } catch (\Throwable $th) {
            return redirect()->route('import.la07.show', $id)->with('success', __('label.FAIL_UPDATE_MESSAGE'));
        }
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // ------------------------------------------------------------------------
    public function destroy($id)
    {
        // --------------------------------------------------------------------
        $data = new \stdClass;
        // --------------------------------------------------------------------
        $siswaAktifDetail = SiswaAktifDetail::findOrFail($id);
        // --------------------------------------------------------------------
        $siswaAktifDetail->delete();
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
}
// ----------------------------------------------------------------------------