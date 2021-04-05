<?php
// ----------------------------------------------------------------------------
namespace App\Http\Controllers\Backend\Import\Summary;
// ----------------------------------------------------------------------------
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// ----------------------------------------------------------------------------
use App\Helpers\ImportHelper;
// ----------------------------------------------------------------------------
use App\Models\Cabang;
use App\Models\Summary;
use App\Models\SummarySAMateri;
use App\Models\SummarySAPendidikan;
// ----------------------------------------------------------------------------
use Carbon\Carbon;
use Auth;
// ----------------------------------------------------------------------------
class SummaryController extends Controller
{
    // ------------------------------------------------------------------------
    public function index()
    {
        // --------------------------------------------------------------------
        $data = new \stdClass; $filtering = new \stdClass;
        $data->title        = "Summary - List";
        $data->filtering    = $filtering; 
        // --------------------------------------------------------------------
        // Filtering data
        // --------------------------------------------------------------------
        $filtering->bulan   = $this->monthArray();
        $filtering->status  = ['Pending', 'Approve'];
        // --------------------------------------------------------------------
        return view('backend.import.summary.index', (array) $data);
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
                $summarys = Summary::with('summary_sa_materi', 'summary_sa_pendidikan', 'cabang', 'user', 'user_approve')->select('summary.*');
                // ------------------------------------------------------------
                // Set level view for list
                // ------------------------------------------------------------
                if(Auth::user()->level_id == 4){
                    $summarys->where('cabang_id', Auth::user()->cabang_id);
                }
                // ------------------------------------------------------------
                $datatable = datatables()->of($summarys)->addIndexColumn();
                // ------------------------------------------------------------
                // Add column
                // ------------------------------------------------------------
                $datatable = $datatable->addColumn('status', function($row){
                                    if($row->status == 0) return "Pending";
                                    else return "Approve";
                                });
                // ------------------------------------------------------------
                $datatable = $datatable->addColumn('action', function($row){
                                    $button = '<div class="btn-group" role="group" aria-label="Basic example">';
                                    $button .= '<a href="'.route('import.summary.show', $row->id).'" class="btn btn-sm btn-info"><i class="ti-eye"></i></a>';
                                    $button .= '<a href="'.route('import.summary.edit', $row->id).'" class="btn btn-sm btn-warning '. ($row->status == 1 ? 'disabled' : '') .'"><i class="ti-settings"></i></a>';
                                    $button .= '<button type="button" data-id="'.$row->id.'" class="btn btn-sm btn-danger btn-delete" '. ($row->status == 1 ? 'disabled' : '') .'><i class="ti-trash"></i></button>';
                                    $button .= '</div>';

                                    return $button;
                                });
                // ------------------------------------------------------------

                // ------------------------------------------------------------
                // Filter column
                // ------------------------------------------------------------
                $datatable = $datatable->filterColumn('status', function($query,$keyword){
                                    $value = 0;
                                    if($keyword == "Approve") $value = 1;
                                    $query->where('status', $value);
                                });
                // ------------------------------------------------------------
                $datatable = $datatable->filterColumn('bulan', function($query, $keyword){
                    $value = array_search($keyword, $this->monthArray());
                    $query->where('bulan', $value);
                });
                // ------------------------------------------------------------
                return $datatable->rawColumns(['approve', 'action'])->make(true);
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
    public function destroy($id)
    {
        // --------------------------------------------------------------------
        $data = new \stdClass;
        // --------------------------------------------------------------------
        $summary = Summary::findOrFail($id);
        // --------------------------------------------------------------------
        $summary->delete();
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