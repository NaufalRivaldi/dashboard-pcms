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
use App\Models\Summary;
use App\Models\SummarySAMateri;
use App\Models\SummarySAPendidikan;
use App\Models\Cabang;
use App\Models\Pembayaran;
use App\Models\SiswaAktif;
use App\Models\SiswaAktifPendidikan;
use App\Models\SiswaBaru;
use App\Models\SiswaInaktif;
use App\Models\SiswaCuti;
// ----------------------------------------------------------------------------
use Carbon\Carbon;
use Auth;
// ----------------------------------------------------------------------------
class SummaryDetailController extends Controller
{
    // ------------------------------------------------------------------------
    public function index($id)
    {
        // --------------------------------------------------------------------
        $data = new \stdClass;
        $data->title        = "Summary - Detail";
        $data->summary      = Summary::with('summary_sa_materi', 'summary_sa_pendidikan')->where('id', $id)->first();
        // --------------------------------------------------------------------
        return view('backend.import.summary.show', (array) $data);
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    public function approve(Request $request, $id)
    {
        $data = new \stdClass;
        // --------------------------------------------------------------------
        // Use try catch
        // --------------------------------------------------------------------
        try {
            // ----------------------------------------------------------------
            // Update summary
            // ----------------------------------------------------------------
            $summary = Summary::find($id);
            $summary->status = 1;
            $summary->user_approve_id = Auth::user()->id;
            $summary->save();
            // ----------------------------------------------------------------
            $data->message = __('label.SUCCESS_UPDATE_MESSAGE');
            return response()->json($data);
            // ----------------------------------------------------------------
        } catch (\Throwable $th) {
            $data->message = __('label.FAIL_UPDATE_MESSAGE');
            return response()->json($data);
        }
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------
}
// ----------------------------------------------------------------------------