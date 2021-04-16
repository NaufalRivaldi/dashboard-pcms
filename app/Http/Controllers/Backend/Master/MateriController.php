<?php
// ----------------------------------------------------------------------------
namespace App\Http\Controllers\Backend\Master;
// ----------------------------------------------------------------------------
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// ----------------------------------------------------------------------------
use App\Models\Kategori;
use App\Models\Materi;
use App\Models\Grade;
use App\Models\MateriGrade;
// ----------------------------------------------------------------------------
use Carbon\Carbon;
// ----------------------------------------------------------------------------
class MateriController extends Controller
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
        $data->title        = "Materi - List";
        $data->filtering    = $filtering; 
        // --------------------------------------------------------------------
        // Filtering data
        // --------------------------------------------------------------------
        $filtering->status      = ['Active', 'Inactive'];
        $filtering->kategori    = Kategori::where('status', 1)->pluck('nama','id');
        // --------------------------------------------------------------------
        return view('backend.master.materi.index', (array) $data);
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
                $materis = Materi::with('kategori', 'materi_grades')
                            ->select('materi.*')
                            ->withCount('materi_grades');
                // ------------------------------------------------------------
                $datatable = datatables()->of($materis)->addIndexColumn();
                // ------------------------------------------------------------
                // Add column
                // ------------------------------------------------------------
                $datatable = $datatable->addColumn('status', function($row){
                                    return statusButton($row->status, $row->id);
                                });
                // ------------------------------------------------------------
                $datatable = $datatable->addColumn('action', function($row){
                                    $button = '<div class="btn-group" role="group" aria-label="Basic example">';
                                    $button .= '<a href="'.route('master.materi.show', $row->id).'" class="btn btn-sm btn-info" title="Lihat materi"><i class="ti-eye"></i></a>';
                                    $button .= '<a href="'.route('master.materi.edit', $row->id).'" class="btn btn-sm btn-warning"><i class="ti-settings"></i></a>';
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
        $data->title        = "Materi - Form";
        $data->materi       = new Materi();
        $data->kategori     = Kategori::where('status', 1)->pluck('nama','id');
        // --------------------------------------------------------------------
        return view('backend.master.materi.form', (array) $data);
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
            'nama'              => 'required|max:191',
            'kategori_id'       => 'required',
        ])->validate();
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Use try catch
        // --------------------------------------------------------------------
        try {
            // ----------------------------------------------------------------
            Materi::create($request->all());
            // ----------------------------------------------------------------
            return redirect()->route('master.materi.index')->with('success', __('label.SUCCESS_CREATE_MESSAGE'));
            // ----------------------------------------------------------------
        } catch (\Throwable $th) {
            return redirect()->route('master.materi.create')->with('danger', __('label.FAIL_CREATE_MESSAGE'));
        }
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    public function storeGrade(Request $request)
    {
        // --------------------------------------------------------------------
        // Set validation
        // --------------------------------------------------------------------
        Validator::make($request->all(), [
            'kode_materi'   => 'required|max:191',
            'kode_grade'    => 'required|max:191',
            'grade_id'      => 'required',
            'materi_id'     => 'required',
            'biaya'         => 'required',
        ])->validate();
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        $data = new \stdClass;
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Use try catch
        // --------------------------------------------------------------------
        try {
            // ----------------------------------------------------------------
            $create = MateriGrade::create($request->all());
            // ----------------------------------------------------------------
            $data->message = __('label.SUCCESS_CREATE_MESSAGE');
            // ----------------------------------------------------------------
            $data->status       = true;
            $data->grade_id     = MateriGrade::where('materi_id', $request->materi_id)->pluck('grade_id')->toArray();
            $data->materiGrades = MateriGrade::with('materi', 'grade')
                                    ->where('materi_id', $request->materi_id)
                                    ->orderBy(
                                        Grade::select( 'nama' )
                                            ->whereColumn( 'grade.id', 'materi_grade.grade_id' ),
                                            'asc'
                                    )
                                    ->get();
            // ----------------------------------------------------------------
            return response()->json($data);
            // ----------------------------------------------------------------
        } catch (\Throwable $th) {
            // ----------------------------------------------------------------
            $data->message = __('label.FAIL_CREATE_MESSAGE');
            // ----------------------------------------------------------------
            $data->status = false;
            // ----------------------------------------------------------------
            return response()->json($data);
            // ----------------------------------------------------------------
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
        // --------------------------------------------------------------------
        $data = new \stdClass;
        $data->title        = "Materi - Detail";
        $data->materi       = Materi::find($id);
        $data->grade        = Grade::where('status', 1)->get();
        $data->grade_id     = MateriGrade::where('materi_id', $id)->pluck('grade_id')->toArray();
        $data->materiGrade  = MateriGrade::with('materi', 'grade')
                                ->where('materi_id', $id)
                                ->orderBy(
                                    Grade::select( 'nama' )
                                        ->whereColumn( 'grade.id', 'materi_grade.grade_id' ),
                                        'asc'
                                )
                                ->get();
        // --------------------------------------------------------------------
        return view('backend.master.materi.show', (array) $data);
        // -------------------------------------------  -------------------------
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
        $data->title        = "Materi - Form Edit";
        $data->materi       = Materi::find($id);
        $data->kategori     = Kategori::where('status', 1)->pluck('nama','id');
        // --------------------------------------------------------------------
        return view('backend.master.materi.form', (array) $data);
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
            'nama'              => 'required|max:191',
            'kategori_id'       => 'required',
        ])->validate();
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Use try catch
        // --------------------------------------------------------------------
        try {
            // ----------------------------------------------------------------
            $data = $request->all();
            // ----------------------------------------------------------------
            $materi                 = Materi::findOrFail($id);
            $materi->nama           = $data['nama'];
            $materi->kategori_id    = $data['kategori_id'];
            $materi->save();
            // ----------------------------------------------------------------
            return redirect()->route('master.materi.index')->with('success', __('label.SUCCESS_UPDATE_MESSAGE'));
            // ----------------------------------------------------------------
        } catch (\Throwable $th) {
            return redirect()->route('master.materi.edit', $id)->with('danger', __('label.FAIL_UPDATE_MESSAGE'));
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
        $materi = Materi::find($id);
        // --------------------------------------------------------------------
        $materi->status = $type;
        $materi->save();
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
        $materi = Materi::findOrFail($id);
        // --------------------------------------------------------------------
        $materi->delete();
        // --------------------------------------------------------------------
        $data->message = __('label.SUCCESS_DELETE_MESSAGE');
        // --------------------------------------------------------------------
        return response()->json($data);
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    public function destroyGrade($id)
    {
        // --------------------------------------------------------------------
        $data = new \stdClass; $materiId = null;
        // --------------------------------------------------------------------
        $materiGrade = MateriGrade::findOrFail($id);
        $materiId    = $materiGrade->materi_id;
        // --------------------------------------------------------------------
        $materiGrade->delete();
        // --------------------------------------------------------------------
        $data->message      = __('label.SUCCESS_DELETE_MESSAGE');
        $data->grade_id     = MateriGrade::where('materi_id', $materiId)->pluck('grade_id')->toArray();
        $data->materiGrades = MateriGrade::with('materi', 'grade')
                                ->where('materi_id', $materiId)
                                ->orderBy(
                                    Grade::select( 'nama' )
                                        ->whereColumn( 'grade.id', 'materi_grade.grade_id' ),
                                        'asc'
                                )
                                ->get();
        // --------------------------------------------------------------------
        return response()->json($data);
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------
}
// ----------------------------------------------------------------------------