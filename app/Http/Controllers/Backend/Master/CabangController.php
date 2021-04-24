<?php
// ----------------------------------------------------------------------------
namespace App\Http\Controllers\Backend\Master;
// ----------------------------------------------------------------------------
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// ----------------------------------------------------------------------------
use App\Models\Cabang;
use App\Models\Wilayah;
use App\Models\SubWilayah;
use App\Models\User;
// ----------------------------------------------------------------------------
use Carbon\Carbon;
// ----------------------------------------------------------------------------
class CabangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // ------------------------------------------------------------------------
    public function index()
    {
        // --------------------------------------------------------------------
        $data = new \stdClass; $filtering = new \stdClass;
        $data->title        = "Cabang - List";
        $data->filtering    = $filtering; 
        // --------------------------------------------------------------------
        // Filtering data
        // --------------------------------------------------------------------
        $filtering->status      = ['Active', 'Inactive'];
        $filtering->wilayah     = Wilayah::where('status', 1)->pluck('nama', 'id');
        $filtering->subWilayah  = SubWilayah::where('status', 1)->pluck('nama', 'id');
        // --------------------------------------------------------------------
        return view('backend.master.cabang.index', (array) $data);
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
                $cabang = Cabang::with('wilayah', 'sub_wilayah', 'owner')->select('cabang.*');
                // ------------------------------------------------------------
                $datatable = datatables()->of($cabang)->addIndexColumn();
                // ------------------------------------------------------------
                // Add column
                // ------------------------------------------------------------
                $datatable = $datatable->addColumn('status', function($row){
                                    return statusButton($row->status, $row->id);
                                });
                // ------------------------------------------------------------
                $datatable = $datatable->addColumn('action', function($row){
                                    $button = '<div class="btn-group" role="group" aria-label="Basic example">';
                                    $button .= '<a href="'.route('master.cabang.edit', $row->id).'" class="btn btn-sm btn-warning"><i class="ti-settings"></i></a>';
                                    // $button .= '<button type="button" data-id="'.$row->id.'" class="btn btn-sm btn-danger btn-delete"><i class="ti-trash"></i></button>';
                                    $button .= '</div>';

                                    return $button;
                                });
                // ------------------------------------------------------------

                // ------------------------------------------------------------
                // Filter column
                // ------------------------------------------------------------
                $datatable = $datatable->filterColumn('status', function($query,$keyword){
                                    $val = 1;
                                    if($keyword == 'Inactive'){
                                        $val = 0;
                                    }

                                    $query->where('status', $val);
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // ------------------------------------------------------------------------
    public function create()
    {
        // --------------------------------------------------------------------
        $data = new \stdClass;
        $data->title        = "Cabang - Form";
        $data->cabang       = new Cabang();
        $data->wilayah      = Wilayah::where('status', 1)->pluck('nama', 'id')->toArray();
        $data->subWilayah   = SubWilayah::where('status', 1)->pluck('nama', 'id')->toArray();
        $data->owner        = User::where('status', 1)->where('level_id', 2)->pluck('nama', 'id')->toArray();
        // --------------------------------------------------------------------
        return view('backend.master.cabang.form', (array) $data);
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // ------------------------------------------------------------------------
    public function store(Request $request)
    {
        // --------------------------------------------------------------------
        // Set validation
        // --------------------------------------------------------------------
        Validator::make($request->all(), [
            'kode'              => 'required|unique:cabang,kode|max:100',
            'nama'              => 'required|max:191',
            'wilayah_id'        => 'required',
            'sub_wilayah_id'    => 'required',
            'user_id'           => 'required',
        ])->validate();
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Use try catch
        // --------------------------------------------------------------------
        try {
            // ----------------------------------------------------------------
            Cabang::create($request->all());
            // ----------------------------------------------------------------
            return redirect()->route('master.cabang.index')->with('success', __('label.SUCCESS_CREATE_MESSAGE'));
            // ----------------------------------------------------------------
        } catch (\Throwable $th) {
            return redirect()->route('master.cabang.create')->with('danger', __('label.FAIL_CREATE_MESSAGE'));
        }
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // ------------------------------------------------------------------------
    public function show($id)
    {
        //
    }
    // ------------------------------------------------------------------------

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // ------------------------------------------------------------------------
    public function edit($id)
    {
        // --------------------------------------------------------------------
        $data = new \stdClass;
        $data->title        = "Cabang - Form Edit";
        $data->cabang       = Cabang::find($id);
        $data->wilayah      = Wilayah::where('status', 1)->pluck('nama', 'id')->toArray();
        $data->subWilayah   = SubWilayah::where('status', 1)->pluck('nama', 'id')->toArray();
        $data->owner        = User::where('status', 1)->where('level_id', 2)->pluck('nama', 'id')->toArray();
        // --------------------------------------------------------------------
        return view('backend.master.cabang.form', (array) $data);
        // -------------------------------------------  -------------------------
    }
    // ------------------------------------------------------------------------

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // ------------------------------------------------------------------------
    public function update(Request $request, $id)
    {
        // --------------------------------------------------------------------
        // Set validation
        // --------------------------------------------------------------------
        Validator::make($request->all(), [
            'kode'      => 'required|unique:cabang,kode,'.$id.'|max:100',
            'nama'      => 'required|max:191',
            'wilayah_id'        => 'required',
            'sub_wilayah_id'    => 'required',
            'user_id'           => 'required',
        ])->validate();
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Use try catch
        // --------------------------------------------------------------------
        try {
            // ----------------------------------------------------------------
            $data = $request->all();
            // ----------------------------------------------------------------
            $cabang = Cabang::findOrFail($id);
            $cabang->kode               = $data['kode'];
            $cabang->nama               = $data['nama'];
            $cabang->latitude           = $data['latitude'];
            $cabang->longitude          = $data['longitude'];
            $cabang->wilayah_id         = $data['wilayah_id'];
            $cabang->sub_wilayah_id     = $data['sub_wilayah_id'];
            $cabang->user_id            = $data['user_id'];
            $cabang->save();
            // ----------------------------------------------------------------
            return redirect()->route('master.cabang.index')->with('success', __('label.SUCCESS_UPDATE_MESSAGE'));
            // ----------------------------------------------------------------
        } catch (\Throwable $th) {
            return redirect()->route('master.cabang.edit', $id)->with('danger', __('label.FAIL_UPDATE_MESSAGE'));
        }
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    // Update status function
    // ------------------------------------------------------------------------
    public function updateStatus($type, $id){
        // --------------------------------------------------------------------
        $data = new \stdClass;
        // --------------------------------------------------------------------
        $cabang = Cabang::find($id);
        // --------------------------------------------------------------------
        $cabang->status = $type;
        $cabang->save();
        // --------------------------------------------------------------------
        $data->message = __('label.SUCCESS_UPDATE_MESSAGE');
        // --------------------------------------------------------------------
        return response()->json($data);
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
        $cabang = Cabang::findOrFail($id);
        // --------------------------------------------------------------------
        $cabang->delete();
        // --------------------------------------------------------------------
        $data->message = __('label.SUCCESS_DELETE_MESSAGE');
        // --------------------------------------------------------------------
        return response()->json($data);
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------
}
// ----------------------------------------------------------------------------