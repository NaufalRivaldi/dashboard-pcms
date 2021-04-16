<?php
// ----------------------------------------------------------------------------
namespace App\Http\Controllers\Backend\Master;
// ----------------------------------------------------------------------------
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// ----------------------------------------------------------------------------
use Auth;
// ----------------------------------------------------------------------------
use App\Models\User;
use App\Models\Level;
use App\Models\Cabang;
// ----------------------------------------------------------------------------
class UserController extends Controller
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
        $data->title        = "User - List";
        $data->filtering    = $filtering; 
        // --------------------------------------------------------------------
        // Filtering data
        // --------------------------------------------------------------------
        $filtering->status      = ['Active', 'Inactive'];
        $filtering->level       = Level::pluck('nama','id');
        // --------------------------------------------------------------------
        return view('backend.master.user.index', (array) $data);
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
                $users = User::with('level', 'cabang_user')
                            ->select('user.*');
                // ------------------------------------------------------------
                $datatable = datatables()->of($users)->addIndexColumn();
                // ------------------------------------------------------------
                // Add column
                // ------------------------------------------------------------
                $datatable = $datatable->addColumn('status', function($row){
                                    return statusButton($row->status, $row->id);
                                });
                // ------------------------------------------------------------
                $datatable = $datatable->addColumn('action', function($row){
                                    $button = '<div class="btn-group" role="group" aria-label="Basic example">';
                                    $button .= '<button data-id="'.$row->id.'" class="btn btn-sm btn-info btn-password " title="Reset password" '.(Auth::user()->id == $row->id ? 'disabled' : '').'><i class="ti-key"></i></button>';
                                    $button .= '<a href="'.route('master.user.edit', $row->id).'" class="btn btn-sm btn-warning"><i class="ti-settings"></i></a>';
                                    // $button .= '<button type="button" data-id="'.$row->id.'" class="btn btn-sm btn-danger btn-delete" '.(Auth::user()->id == $row->id ? 'disabled' : '').'><i class="ti-trash"></i></button>';
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
        $data->title        = "User - Form";
        $data->user         = new User();
        $data->level        = Level::pluck('nama','id');
        $data->cabang       = Cabang::pluck('nama','id');
        // --------------------------------------------------------------------
        return view('backend.master.user.form', (array) $data);
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
            'username'          => 'required|max:191',
            'email'             => 'required|email|unique:user,email',
            'cabang_id'         => 'nullable',
            'level_id'          => 'required',
        ])->validate();
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Use try catch
        // --------------------------------------------------------------------
        try {
            // ----------------------------------------------------------------
            $data = $request->all();
            $data['password'] = bcrypt('123456');
            if($data['level_id'] != 4) $data['cabang_id'] = null;
            // ----------------------------------------------------------------
            User::create($data);
            // ----------------------------------------------------------------
            return redirect()->route('master.user.index')->with('success', __('label.SUCCESS_CREATE_MESSAGE'));
            // ----------------------------------------------------------------
        } catch (\Throwable $th) {
            return redirect()->route('master.user.create')->with('danger', __('label.FAIL_CREATE_MESSAGE'));
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
        $data->title        = "User - Form Edit";
        $data->user         = User::find($id);
        $data->level        = Level::pluck('nama','id');
        $data->cabang       = Cabang::pluck('nama','id');
        // --------------------------------------------------------------------
        return view('backend.master.user.form', (array) $data);
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
            'username'          => 'required|max:191',
            'email'             => 'required|email|unique:user,email,'.$id,
            'level_id'          => 'required',
        ])->validate();
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Use try catch
        // --------------------------------------------------------------------
        try {
            // ----------------------------------------------------------------
            $data = $request->all();
            // ----------------------------------------------------------------
            $user                       = User::findOrFail($id);
            $user->nama                 = $data['nama'];
            $user->username             = $data['username'];
            $user->email                = $data['email'];
            $user->cabang_id            = $data['level_id'] != 4 ? null : $data['cabang_id'];
            $user->level_id             = $data['level_id'];
            $user->save();
            // ----------------------------------------------------------------
            return redirect()->route('master.user.index')->with('success', __('label.SUCCESS_UPDATE_MESSAGE'));
            // ----------------------------------------------------------------
        } catch (\Throwable $th) {
            return redirect()->route('master.user.edit', $id)->with('danger', __('label.FAIL_UPDATE_MESSAGE'));
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
        $user = User::find($id);
        // --------------------------------------------------------------------
        $user->status = $type;
        $user->save();
        // --------------------------------------------------------------------
        $data->message = __('label.SUCCESS_UPDATE_MESSAGE');
        // --------------------------------------------------------------------
        return response()->json($data);
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    // Reset password function
    // ------------------------------------------------------------------------
    public function resetPassword($id){
        // --------------------------------------------------------------------
        $data = new \stdClass;
        // --------------------------------------------------------------------
        $user = User::find($id);
        // --------------------------------------------------------------------
        $user->password = bcrypt('123456');
        $user->save();
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
        $user = User::findOrFail($id);
        // --------------------------------------------------------------------
        $user->delete();
        // --------------------------------------------------------------------
        $data->message = __('label.SUCCESS_DELETE_MESSAGE');
        // --------------------------------------------------------------------
        return response()->json($data);
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------
}
// ----------------------------------------------------------------------------