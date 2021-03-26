<?php
// ----------------------------------------------------------------------------
namespace App\Http\Controllers\Backend\Import\LA03;
// ----------------------------------------------------------------------------
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Imports\LA03Import;
use Maatwebsite\Excel\Facades\Excel;
// ----------------------------------------------------------------------------
use App\Helpers\ImportHelper;
// ----------------------------------------------------------------------------
use App\Models\Pembayaran; // LA03 - model
use App\Models\PembayaranDetail; // LA03 - model
use App\Models\VWPembayaran; // LA03 import - model
use App\Models\Cabang;
// ----------------------------------------------------------------------------
use Carbon\Carbon;
use Auth;
// ----------------------------------------------------------------------------
class LA03Controller extends Controller
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
        $data->title        = "LA03 - List";
        $data->filtering    = $filtering; 
        // --------------------------------------------------------------------
        // Filtering data
        // --------------------------------------------------------------------
        $filtering->cabang  = Cabang::pluck('nama', 'id');
        $filtering->bulan   = $this->monthArray();
        $filtering->status  = ["Pending", "Accept"];
        $filtering->type    = ["Penerimaan Uang Pendaftaran", "Penerimaan Uang Kursus"];
        // --------------------------------------------------------------------
        return view('backend.import.la03.index', (array) $data);
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    // Check date
    // ------------------------------------------------------------------------
    public function checkDataValidation(Request $request){
        // --------------------------------------------------------------------
        $data = new \stdClass;
        // --------------------------------------------------------------------
        $input  = $request->all();
        $month  = Carbon::parse('01 '.$input['date'])->format('m');
        $year   = Carbon::parse('01 '.$input['date'])->format('Y');
        // --------------------------------------------------------------------
        $pembayaran = Pembayaran::where('bulan', $month)->where('tahun', $year)->first();
        if(empty($pembayaran)){
            $data->status = true;
            $data->message = "Data masih kosong, pembuatan form bisa dilakukan.";
        }else{
            $data->status = false;
            $data->message = "Data sudah terisi, mohon cek kembali pada sistem!";
        }
        // --------------------------------------------------------------------
        return response()->json($data);
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
                $pembayarans = Pembayaran::with('cabang', 'user');
                // ------------------------------------------------------------
                $datatable = datatables()->of($pembayarans)->addIndexColumn();
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
                                    $button .= '<a href="'.route('import.la03.show', $row->id).'" class="btn btn-sm btn-info"><i class="ti-eye"></i></a>';
                                    $button .= '<a href="'.route('import.la03.edit', $row->id).'" class="btn btn-sm btn-warning"><i class="ti-settings"></i></a>';
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
        $data->title            = "LA03 - Form";
        $data->pembayaran       = new Pembayaran();
        $data->pembayaranDetail = [];
        // --------------------------------------------------------------------
        // Init data
        // --------------------------------------------------------------------
        $data->pageType = "create";
        $data->cabangs = Cabang::where('status', 1)->pluck('nama', 'id');
        // --------------------------------------------------------------------
        return view('backend.import.la03.form', (array) $data);
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    public function import()
    {
        // --------------------------------------------------------------------
        $data = new \stdClass;
        $data->title        = "LA03 - Import";
        // --------------------------------------------------------------------
        return view('backend.import.la03.import', (array) $data);
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
            'bulan_tahun'       => 'required',
            'cabang_id'         => 'required',
            'type.*'            => 'required',
            'nominal.*'         => 'required|numeric',
        ])->validate();
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Use try catch
        // --------------------------------------------------------------------
        try {
            // ----------------------------------------------------------------
            // Create pembayaran
            // ----------------------------------------------------------------
            $input = $request->all();
            $pembayaran = [
                "bulan"     => Carbon::parse('01 '.$input['bulan_tahun'])->format('m'),
                "tahun"     => Carbon::parse('01 '.$input['bulan_tahun'])->format('Y'),
                "cabang_id" => $input['cabang_id'],
                "user_id"   => Auth::user()->id,
            ];
            // ----------------------------------------------------------------
            $mPembayaran = Pembayaran::create($pembayaran);
            // ----------------------------------------------------------------

            // ----------------------------------------------------------------
            // Create pembayaran detail
            // ----------------------------------------------------------------
            if(count($input['type']) > 0){
                for($i = 0; $i < count($input['type']); $i++){
                    $pembayaranDetail = [
                        "type"          => $input['type'][$i],
                        "nama_pembayar" => $input['nama_pembayar'][$i],
                        "nominal"       => $input['nominal'][$i],
                        "pembayaran_id" => $mPembayaran->id,
                    ];
                    // --------------------------------------------------------
                    PembayaranDetail::create($pembayaranDetail);
                    // --------------------------------------------------------
                }
            }
            // ----------------------------------------------------------------
            return redirect()->route('import.la03.index')->with('success', __('label.SUCCESS_CREATE_MESSAGE'));
            // ----------------------------------------------------------------
        } catch (\Throwable $th) {
            dd($th);
            return redirect()->route('import.la03.index')->with('success', __('label.FAIL_CREATE_MESSAGE'));
        }
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
            VWPembayaran::truncate(); // Delete all data on import table
            // ----------------------------------------------------------------

            // ----------------------------------------------------------------
            // Import data & set to database penjualan and penjualan detail
            // ----------------------------------------------------------------
            $file = $request->file('file_import');
            Excel::import(new LA03Import, $file);
            // ----------------------------------------------------------------
            $vwPembayarans = VWPembayaran::all();
            // ----------------------------------------------------------------
            // Check cabang if it's exits
            // ----------------------------------------------------------------
            $cabang = Cabang::where('kode', $vwPembayarans->random()->cabang)->where('status', 1)->first();
            if(empty($cabang)){
                $cabang = ImportHelper::createCabang($vwPembayarans->random()->cabang);
            }
            // ----------------------------------------------------------------
            // Check pembayaran
            // ----------------------------------------------------------------
            $pembayaran = Pembayaran::where('bulan', $vwPembayarans->random()->bulan)->where('tahun', $vwPembayarans->random()->tahun)->where('cabang_id', $cabang->id)->where('status', 1)->first();
            
            if(empty($pembayaran)){
                // ------------------------------------------------------------
                $pembayaran = Pembayaran::where('bulan', $vwPembayarans->random()->bulan)->where('tahun', $vwPembayarans->random()->tahun)->where('cabang_id', $cabang->id)->where('status', 0)->first();
                // ------------------------------------------------------------
                if(empty($pembayaran)){
                    $pembayaran = Pembayaran::create([
                        'bulan'         => $vwPembayarans->random()->bulan,
                        'tahun'         => $vwPembayarans->random()->tahun,
                        'status'        => 0,
                        'user_id'       => Auth::user()->id,
                        'cabang_id'     => $cabang->id,
                    ]);
                }else{
                    PembayaranDetail::where('pembayaran_id', $pembayaran->id)->delete();
                }
                // ------------------------------------------------------------
                // Insert data Penjualan Detail
                // ------------------------------------------------------------
                foreach($vwPembayarans as $vwPembayaran){
                    PembayaranDetail::create([
                        'type'              => $vwPembayaran->type,
                        'nama_pembayar'     => $vwPembayaran->nama_pembayar,
                        'nominal'           => $vwPembayaran->nominal,
                        'pembayaran_id'     => $pembayaran->id,
                        'materi_grade_id'   => null,
                    ]);
                }
                // ------------------------------------------------------------
            }else{
                return redirect()->route('import.la03.index')->with('info', 'Data sudah ada dan sudah di approve');
            }
            // ----------------------------------------------------------------
            
            // ----------------------------------------------------------------
            return redirect()->route('import.la03.index')->with('success', __('label.SUCCESS_CREATE_MESSAGE'));
            // ----------------------------------------------------------------
        } catch (\Throwable $th) {
            return redirect()->route('import.la03.index')->with('success', __('label.FAIL_CREATE_MESSAGE'));
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
        $data = new \stdClass; $filtering = new \stdClass;
        $data->title        = "LA03 - Detail";
        $data->filtering    = $filtering; 
        $data->pembayaran   = Pembayaran::with('pembayaran_details')->where('id', $id)->first();
        // --------------------------------------------------------------------
        // Filtering data
        // --------------------------------------------------------------------
        $filtering->type    = ["Penerimaan Uang Pendaftaran", "Penerimaan Uang Kursus"];
        // --------------------------------------------------------------------
        return view('backend.import.la03.show', (array) $data);
        // -------------------------------------------  -----------------------
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
        $data->title            = "LA03 - Edit Form";
        $data->pembayaran       = Pembayaran::find($id);
        $data->pembayaranDetail = PembayaranDetail::where('pembayaran_id', $id)->get();
        // --------------------------------------------------------------------
        // Init data
        // --------------------------------------------------------------------
        $data->pageType = "edit";
        $data->cabangs = Cabang::where('status', 1)->pluck('nama', 'id');
        // --------------------------------------------------------------------
        return view('backend.import.la03.form', (array) $data);
        // --------------------------------------------------------------------
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
            'id'                => 'required',
            'bulan_tahun'       => 'required',
            'cabang_id'         => 'required',
            'type.*'            => 'required',
            'nominal.*'         => 'required|numeric',
        ])->validate();
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Use try catch
        // --------------------------------------------------------------------
        try {
            // ----------------------------------------------------------------
            // Update pembayaran
            // ----------------------------------------------------------------
            $input = $request->all();
            $pembayaran = [
                "bulan"     => Carbon::parse('01 '.$input['bulan_tahun'])->format('m'),
                "tahun"     => Carbon::parse('01 '.$input['bulan_tahun'])->format('Y'),
                "cabang_id" => $input['cabang_id'],
                "user_id"   => Auth::user()->id,
            ];
            // ----------------------------------------------------------------
            $pembayaran = Pembayaran::find($input['id']);
            $pembayaran->bulan      = Carbon::parse('01 '.$input['bulan_tahun'])->format('m');
            $pembayaran->tahun      = Carbon::parse('01 '.$input['bulan_tahun'])->format('Y');
            $pembayaran->cabang_id  = $input['cabang_id'];
            $pembayaran->user_id    = Auth::user()->id;
            $pembayaran->save();
            // ----------------------------------------------------------------

            // ----------------------------------------------------------------
            // Create pembayaran detail
            // ----------------------------------------------------------------
            PembayaranDetail::where('pembayaran_id', $input['id'])->delete();
            if(count($input['type']) > 0){
                for($i = 0; $i < count($input['type']); $i++){
                    $pembayaranDetail = [
                        "type"          => $input['type'][$i],
                        "nama_pembayar" => $input['nama_pembayar'][$i],
                        "nominal"       => $input['nominal'][$i],
                        "pembayaran_id" => $input['id'],
                    ];
                    // --------------------------------------------------------
                    PembayaranDetail::create($pembayaranDetail);
                    // --------------------------------------------------------
                }
            }
            // ----------------------------------------------------------------
            return redirect()->route('import.la03.index')->with('success', __('label.SUCCESS_UPDATE_MESSAGE'));
            // ----------------------------------------------------------------
        } catch (\Throwable $th) {
            return redirect()->route('import.la03.index')->with('success', __('label.FAIL_UPDATE_MESSAGE'));
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
        $pembayaran = Pembayaran::findOrFail($id);
        // --------------------------------------------------------------------
        $pembayaran->delete();
        // --------------------------------------------------------------------
        $data->message = __('label.SUCCESS_DELETE_MESSAGE');
        // --------------------------------------------------------------------
        return response()->json($data);
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

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