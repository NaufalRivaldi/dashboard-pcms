<?php
// ----------------------------------------------------------------------------
namespace App\Http\Controllers\Backend\Master;
// ----------------------------------------------------------------------------
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// ----------------------------------------------------------------------------
use App\Models\SubWilayah;
// ----------------------------------------------------------------------------
use Carbon\Carbon;
// ----------------------------------------------------------------------------
class SubWilayahController extends Controller
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
        $data->title        = "Sub Wilayah - List";
        $data->filtering    = $filtering; 
        // --------------------------------------------------------------------
        // Filtering data
        // --------------------------------------------------------------------
        $filtering->status = ['Active', 'Inactive'];
        // --------------------------------------------------------------------
        return view('backend.master.sub_wilayah.index', (array) $data);
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
                $subWilayahs = SubWilayah::query();
                // ------------------------------------------------------------
                $datatable = datatables()->of($subWilayahs)->addIndexColumn();
                // ------------------------------------------------------------
                // Add column
                // ------------------------------------------------------------
                $datatable = $datatable->addColumn('status', function($row){
                                    return statusButton($row->status, $row->id);
                                });
                // ------------------------------------------------------------
                $datatable = $datatable->addColumn('action', function($row){
                                    $button = '<div class="btn-group" role="group" aria-label="Basic example">';
                                    $button .= '<a href="'.route('master.sub-wilayah.edit', $row->id).'" class="btn btn-sm btn-warning"><i class="ti-settings"></i></a>';
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
        $data->title        = "Sub Wilayah - Form";
        $data->subWilayah   = new SubWilayah();
        // --------------------------------------------------------------------
        return view('backend.master.sub_wilayah.form', (array) $data);
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
            'kode'      => 'required|unique:sub_wilayah,kode|max:100',
            'nama'      => 'required|max:191',
        ])->validate();
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Use try catch
        // --------------------------------------------------------------------
        try {
            // ----------------------------------------------------------------
            SubWilayah::create($request->all());
            // ----------------------------------------------------------------
            return redirect()->route('master.sub-wilayah.index')->with('success', __('label.SUCCESS_CREATE_MESSAGE'));
            // ----------------------------------------------------------------
        } catch (\Throwable $th) {
            return redirect()->route('master.sub-wilayah.create')->with('danger', __('label.FAIL_CREATE_MESSAGE'));
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
        $data->title        = "Sub Wilayah - Form Edit";
        $data->subWilayah   = SubWilayah::find($id);
        // --------------------------------------------------------------------
        return view('backend.master.sub_wilayah.form', (array) $data);
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
            'kode'      => 'required|unique:sub_wilayah,kode,'.$id.'|max:100',
            'nama'      => 'required|max:191',
        ])->validate();
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Use try catch
        // --------------------------------------------------------------------
        try {
            // ----------------------------------------------------------------
            $data = $request->all();
            // ----------------------------------------------------------------
            $subWilayah = SubWilayah::findOrFail($id);
            $subWilayah->kode = $data['kode'];
            $subWilayah->nama = $data['nama'];
            $subWilayah->save();
            // ----------------------------------------------------------------
            return redirect()->route('master.sub-wilayah.index')->with('success', __('label.SUCCESS_UPDATE_MESSAGE'));
            // ----------------------------------------------------------------
        } catch (\Throwable $th) {
            return redirect()->route('master.sub-wilayah.edit', $id)->with('danger', __('label.FAIL_UPDATE_MESSAGE'));
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
        $subWilayah = SubWilayah::find($id);
        // --------------------------------------------------------------------
        $subWilayah->status = $type;
        $subWilayah->save();
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
        $subWilayah = SubWilayah::findOrFail($id);
        // --------------------------------------------------------------------
        $subWilayah->delete();
        // --------------------------------------------------------------------
        $data->message = __('label.SUCCESS_DELETE_MESSAGE');
        // --------------------------------------------------------------------
        return response()->json($data);
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------
}
// ----------------------------------------------------------------------------