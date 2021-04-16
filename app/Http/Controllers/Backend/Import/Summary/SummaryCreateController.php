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
use App\Models\Materi;
use App\Models\Pendidikan;
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
class SummaryCreateController extends Controller
{
    // ------------------------------------------------------------------------
    public function index()
    {
        // --------------------------------------------------------------------
        $data = new \stdClass;
        $data->title            = "Summary - Form";
        $data->summary          = new Summary();
        $data->summary->summary_sa_materi       = new SummarySAMateri();
        $data->summary->summary_sa_pendidikan   = new SummarySAPendidikan();
        // --------------------------------------------------------------------
        // Init data
        // --------------------------------------------------------------------
        $data->pageType = "create";
        $data->cabangs = Cabang::where('status', 1)->pluck('nama', 'id');
        // --------------------------------------------------------------------
        $data->materis      = Materi::where('status', 1)->get();
        $data->pendidikans  = Pendidikan::all();
        // --------------------------------------------------------------------
        return view('backend.import.summary.form', (array) $data);
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    public function generate()
    {
        // --------------------------------------------------------------------
        $data = new \stdClass;
        $data->title            = "Summary - Generate";
        $data->summary          = new Summary();
        // --------------------------------------------------------------------
        // Init data
        // --------------------------------------------------------------------
        $data->pageType = "create";

        if(Auth::user()->level_id == 2){
            $data->cabangs = Cabang::where('status', 1)->where('user_id', Auth::user()->id)->pluck('nama', 'id');
        }else if(Auth::user()->level_id == 4){
            $data->cabangs = Cabang::where('status', 1)->where('id', Auth::user()->cabang_id)->pluck('nama', 'id');
        }else{
            $data->cabangs = Cabang::where('status', 1)->pluck('nama', 'id');
        }
        // --------------------------------------------------------------------
        return view('backend.import.summary.generate', (array) $data);
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
        // Use try catch
        // --------------------------------------------------------------------
        try {
            $input  = $request->all();
            $month  = Carbon::parse('01 '.$input['date'])->format('m');
            $year   = Carbon::parse('01 '.$input['date'])->format('Y');
            // ----------------------------------------------------------------
            // Check input value
            // ----------------------------------------------------------------
            if(empty($input['date']) || empty($input['cabang_id'])){
                // ------------------------------------------------------------
                $data->status = false;
                $data->message = "Data tidak valid!";
                // ------------------------------------------------------------
                return response()->json($data);
                // ------------------------------------------------------------
            }
            // ----------------------------------------------------------------
            $summary = Summary::where('bulan', $month)->where('tahun', $year)->where('cabang_id', $input['cabang_id'])->first();
            if(empty($summary)){
                // ------------------------------------------------------------
                // Check data for import data
                // ------------------------------------------------------------
                // LA03
                $pembayaran = Pembayaran::where('bulan', $month)->where('tahun', $year)->where('cabang_id', $input['cabang_id'])->first();
                if(!empty($pembayaran)) $data->la03 = $pembayaran;
                else $data->la03 = false;

                // LA06
                $siswaAktif = SiswaAktif::with('siswa_aktif_details.materi')->where('bulan', $month)->where('tahun', $year)->where('cabang_id', $input['cabang_id'])->first();
                if(!empty($siswaAktif)) $data->la06 = $siswaAktif;
                else $data->la06 = false;

                // LA07
                $siswaAktifPendidikan = SiswaAktifPendidikan::with('siswa_aktif_pendidikan_details.pendidikan')->where('bulan', $month)->where('tahun', $year)->where('cabang_id', $input['cabang_id'])->first();
                if(!empty($siswaAktifPendidikan)) $data->la07 = $siswaAktifPendidikan;
                else $data->la07 = false;

                // LA09
                $siswaBaru = SiswaBaru::where('bulan', $month)->where('tahun', $year)->where('cabang_id', $input['cabang_id'])->first();
                if(!empty($siswaBaru)) $data->la09 = $siswaBaru;
                else $data->la09 = false;

                // LA12
                $siswaInaktif = SiswaInaktif::where('bulan', $month)->where('tahun', $year)->where('cabang_id', $input['cabang_id'])->first();
                if(!empty($siswaInaktif)) $data->la12 = $siswaInaktif;
                else $data->la12 = false;

                // LA13
                $siswaCuti = SiswaCuti::where('bulan', $month)->where('tahun', $year)->where('cabang_id', $input['cabang_id'])->first();
                if(!empty($siswaCuti)) $data->la13 = $siswaCuti;
                else $data->la13 = false;
                // ------------------------------------------------------------

                // ------------------------------------------------------------
                $cabang = Cabang::find($input['cabang_id']);
                // ------------------------------------------------------------
                $data->status = true;
                $data->month = $month;
                $data->year = $year;
                $data->cabang = $cabang;
                // ------------------------------------------------------------
                $data->message = "Data masih kosong, pembuatan form bisa dilakukan.";
                // ------------------------------------------------------------
            }else{
                $data->status = false;
                $data->message = "Data sudah terisi, mohon cek kembali pada sistem!";
            }
            // ----------------------------------------------------------------
            return response()->json($data);
            // ----------------------------------------------------------------
        } catch (\Throwable $th) {
            // ----------------------------------------------------------------
            $data->status = false;
            $data->message = "Data tidak valid!";
            // ----------------------------------------------------------------
            return response()->json($data);
            // ----------------------------------------------------------------
        }
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    public function store(Request $request)
    {
        // --------------------------------------------------------------------
        // Set validation
        // --------------------------------------------------------------------
        Validator::make($request->all(), [
            'bulan_tahun'       => 'required',
            'cabang_id'         => 'required',
            'uang_pendaftaran'  => 'required|numeric',
            'uang_kursus'       => 'required|numeric',
            'siswa_aktif'       => 'required|numeric',
            'siswa_baru'        => 'required|numeric',
            'siswa_cuti'        => 'required|numeric',
            'siswa_keluar'      => 'required|numeric',
            'materi_id.*'       => 'required|numeric',
            'pendidikan_id.*'   => 'required|numeric',
            'jumlah_m.*'        => 'required|numeric',
            'jumlah_p.*'        => 'required|numeric',
        ])->validate();
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Use try catch
        // --------------------------------------------------------------------
        try {
            // ----------------------------------------------------------------
            // Create Summary
            // ----------------------------------------------------------------
            $input = $request->all();
            $month = Carbon::parse('01 '.$input['bulan_tahun'])->format('m');
            $year = Carbon::parse('01 '.$input['bulan_tahun'])->format('Y');
            // ----------------------------------------------------------------

            // ----------------------------------------------------------------
            $summary = [
                "bulan"             => $month,
                "tahun"             => $year,
                "uang_pendaftaran"  => $input['uang_pendaftaran'],
                "uang_kursus"       => $input['uang_kursus'],
                "siswa_aktif"       => $input['siswa_aktif'],
                "siswa_baru"        => $input['siswa_baru'],
                "siswa_cuti"        => $input['siswa_cuti'],
                "siswa_keluar"      => $input['siswa_keluar'],
                "status"            => 0,
                "cabang_id"         => $input['cabang_id'],
                "user_id"           => Auth::user()->id,
            ];
            // ----------------------------------------------------------------
            $mSummary = Summary::create($summary);
            // ----------------------------------------------------------------

            // ----------------------------------------------------------------
            // Summary sa materi
            // ----------------------------------------------------------------
            for($i = 0; $i < count($input['materi_id']); $i++){
                $summaryMateri = [
                    "jumlah"            => $input['jumlah_m'][$i],
                    "materi_id"         => $input['materi_id'][$i],
                    "summary_id"        => $mSummary->id,
                ];
                // ------------------------------------------------------------
                SummarySAMateri::create($summaryMateri);
                // ------------------------------------------------------------
            }
            // ----------------------------------------------------------------
            // Summary sa pendidikan
            // ----------------------------------------------------------------
            for($i = 0; $i < count($input['pendidikan_id']); $i++){
                $summaryPendidikan = [
                    "jumlah"            => $input['jumlah_p'][$i],
                    "pendidikan_id"     => $input['pendidikan_id'][$i],
                    "summary_id"        => $mSummary->id,
                ];
                // ------------------------------------------------------------
                SummarySAPendidikan::create($summaryPendidikan);
                // ------------------------------------------------------------
            }
            // ----------------------------------------------------------------
            return redirect()->route('import.summary.index')->with('success', __('label.SUCCESS_CREATE_MESSAGE'));
            // ----------------------------------------------------------------
        } catch (\Throwable $th) {
            return redirect()->route('import.summary.index')->with('success', __('label.FAIL_CREATE_MESSAGE'));
        }
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    public function storeGenerate(Request $request)
    {
        // --------------------------------------------------------------------
        // Set validation
        // --------------------------------------------------------------------
        Validator::make($request->all(), [
            'bulan_tahun'       => 'required',
            'cabang_id'         => 'required',
        ])->validate();
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Use try catch
        // --------------------------------------------------------------------
        try {
            // ----------------------------------------------------------------
            // Create Summary
            // ----------------------------------------------------------------
            $input = $request->all();
            $month = Carbon::parse('01 '.$input['bulan_tahun'])->format('m');
            $year = Carbon::parse('01 '.$input['bulan_tahun'])->format('Y');
            // ----------------------------------------------------------------
            // Set data import
            // ----------------------------------------------------------------
            // LA03
            $pembayaran = Pembayaran::where('bulan', $month)->where('tahun', $year)->where('cabang_id', $input['cabang_id'])->first();

            // LA06
            $siswaAktif = SiswaAktif::where('bulan', $month)->where('tahun', $year)->where('cabang_id', $input['cabang_id'])->first();

            // LA07
            $siswaAktifPendidikan = SiswaAktifPendidikan::where('bulan', $month)->where('tahun', $year)->where('cabang_id', $input['cabang_id'])->first();

            // LA09
            $siswaBaru = SiswaBaru::where('bulan', $month)->where('tahun', $year)->where('cabang_id', $input['cabang_id'])->first();

            // LA12
            $siswaInaktif = SiswaInaktif::where('bulan', $month)->where('tahun', $year)->where('cabang_id', $input['cabang_id'])->first();

            // LA13
            $siswaCuti = SiswaCuti::where('bulan', $month)->where('tahun', $year)->where('cabang_id', $input['cabang_id'])->first();
            // ----------------------------------------------------------------

            // ----------------------------------------------------------------
            // Set calculation data
            // ----------------------------------------------------------------
            $uangPendaftaran = 0;
            foreach($pembayaran->pembayaran_details as $row){
                if($row->type == 1){
                    $uangPendaftaran += $row->nominal;
                }
            }
            // ----------------------------------------------------------------
            $uangKursus = 0;
            foreach($pembayaran->pembayaran_details as $row){
                if($row->type == 2){
                    $uangKursus += $row->nominal;
                }
            }
            // ----------------------------------------------------------------
            $mSummary = Summary::where('bulan', $month)->where('tahun', $year)->where('cabang_id', $input['cabang_id'])->where('status', 0)->first();
            if(empty($mSummary)){
                $summary = [
                    "bulan"             => $month,
                    "tahun"             => $year,
                    "uang_pendaftaran"  => $uangPendaftaran,
                    "uang_kursus"       => $uangKursus,
                    "siswa_aktif"       => $siswaAktif->siswa_aktif_details->sum('jumlah'),
                    "siswa_baru"        => $siswaBaru ? $siswaBaru->sum('jumlah') : 0,
                    "siswa_cuti"        => $siswaCuti ? $siswaCuti->sum('jumlah') : 0,
                    "siswa_keluar"      => $siswaInaktif ? $siswaInaktif->sum('jumlah') : 0,
                    "status"            => 0,
                    "cabang_id"         => $input['cabang_id'],
                    "user_id"           => Auth::user()->id,
                ];
                // ------------------------------------------------------------
                $mSummary = Summary::create($summary);
                // ------------------------------------------------------------
            }else{
                // ------------------------------------------------------------
                SummarySAMateri::where('summary_id', $mSummary->id)->delete();
                SummarySAPendidikan::where('summary_id', $mSummary->id)->delete();
                // ------------------------------------------------------------
                $mSummary->bulan             = $month;
                $mSummary->tahun             = $year;
                $mSummary->uang_pendaftaran  = $uangPendaftaran;
                $mSummary->uang_kursus       = $uangKursus;
                $mSummary->siswa_aktif       = $siswaAktif->siswa_aktif_details->sum('jumlah');
                $mSummary->siswa_baru        = $siswaBaru ? $siswaBaru->sum('jumlah') : 0;
                $mSummary->siswa_cuti        = $siswaCuti ? $siswaCuti->sum('jumlah') : 0;
                $mSummary->siswa_keluar      = $siswaInaktif ? $siswaInaktif->sum('jumlah') : 0;
                $mSummary->status            = 0;
                $mSummary->cabang_id         = $input['cabang_id'];
                $mSummary->user_id           = Auth::user()->id;
                $mSummary->save();
            }
            // ----------------------------------------------------------------

            // ----------------------------------------------------------------
            // Summary sa materi
            // ----------------------------------------------------------------
            foreach($siswaAktif->siswa_aktif_details as $row){
                $summaryMateri = [
                    "jumlah"            => $row->jumlah,
                    "materi_id"         => $row->materi_id,
                    "summary_id"        => $mSummary->id,
                ];
                // ------------------------------------------------------------
                SummarySAMateri::create($summaryMateri);
                // ------------------------------------------------------------
            }
            // ----------------------------------------------------------------
            // Summary sa pendidikan
            // ----------------------------------------------------------------
            foreach($siswaAktifPendidikan->siswa_aktif_pendidikan_details as $row){
                $summaryPendidikan = [
                    "jumlah"            => $row->jumlah,
                    "pendidikan_id"     => $row->pendidikan_id,
                    "summary_id"        => $mSummary->id,
                ];
                // ------------------------------------------------------------
                SummarySAPendidikan::create($summaryPendidikan);
                // ------------------------------------------------------------
            }
            // ----------------------------------------------------------------
            return redirect()->route('import.summary.index')->with('success', __('label.SUCCESS_CREATE_MESSAGE'));
            // ----------------------------------------------------------------
        } catch (\Throwable $th) {
            return redirect()->route('import.summary.index')->with('success', __('label.FAIL_CREATE_MESSAGE'));
        }
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    public function edit($id)
    {
        // --------------------------------------------------------------------
        $data = new \stdClass;
        $data->title            = "Summary - Edit Form";
        $data->summary          = Summary::with('summary_sa_materi', 'summary_sa_pendidikan')->find($id);
        // --------------------------------------------------------------------
        // Init data
        // --------------------------------------------------------------------
        $data->pageType = "edit";

        if(Auth::user()->level_id != 1){
            $data->cabangs = Cabang::where('status', 1)->where('user_id', Auth::user()->id)->pluck('nama', 'id');
        }else{
            $data->cabangs = Cabang::where('status', 1)->pluck('nama', 'id');
        }
        // --------------------------------------------------------------------
        $data->materis      = Materi::where('status', 1)->get();
        $data->pendidikans  = Pendidikan::all();
        // --------------------------------------------------------------------
        return view('backend.import.summary.form', (array) $data);
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
            'bulan_tahun'       => 'required',
            'cabang_id'         => 'required',
            'uang_pendaftaran'  => 'required|numeric',
            'uang_kursus'       => 'required|numeric',
            'siswa_aktif'       => 'required|numeric',
            'siswa_baru'        => 'required|numeric',
            'siswa_cuti'        => 'required|numeric',
            'siswa_keluar'      => 'required|numeric',
            'materi_id.*'       => 'required|numeric',
            'pendidikan_id.*'   => 'required|numeric',
            'jumlah_m.*'        => 'required|numeric',
            'jumlah_p.*'        => 'required|numeric',
        ])->validate();
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Use try catch
        // --------------------------------------------------------------------
        try {
            // ----------------------------------------------------------------
            // Create Summary
            // ----------------------------------------------------------------
            $input = $request->all();
            $month = Carbon::parse('01 '.$input['bulan_tahun'])->format('m');
            $year = Carbon::parse('01 '.$input['bulan_tahun'])->format('Y');
            // ----------------------------------------------------------------

            // ----------------------------------------------------------------
            $mSummary = Summary::findOrFail($id);
            $mSummary->bulan             = $month;
            $mSummary->tahun             = $year;
            $mSummary->uang_pendaftaran  = $input['uang_pendaftaran'];
            $mSummary->uang_kursus       = $input['uang_kursus'];
            $mSummary->siswa_aktif       = $input['siswa_aktif'];
            $mSummary->siswa_baru        = $input['siswa_baru'];
            $mSummary->siswa_cuti        = $input['siswa_cuti'];
            $mSummary->siswa_keluar      = $input['siswa_keluar'];
            $mSummary->status            = 0;
            $mSummary->cabang_id         = $input['cabang_id'];
            $mSummary->user_id           = Auth::user()->id;
            $mSummary->save();
            // ----------------------------------------------------------------

            // ----------------------------------------------------------------
            // Summary sa materi
            // ----------------------------------------------------------------
            SummarySAMateri::where('summary_id', $mSummary->id)->delete();
            for($i = 0; $i < count($input['materi_id']); $i++){
                $summaryMateri = [
                    "jumlah"            => $input['jumlah_m'][$i],
                    "materi_id"         => $input['materi_id'][$i],
                    "summary_id"        => $mSummary->id,
                ];
                // ------------------------------------------------------------
                SummarySAMateri::create($summaryMateri);
                // ------------------------------------------------------------
            }
            // ----------------------------------------------------------------
            // Summary sa pendidikan
            // ----------------------------------------------------------------
            SummarySAPendidikan::where('summary_id', $mSummary->id)->delete();
            for($i = 0; $i < count($input['pendidikan_id']); $i++){
                $summaryPendidikan = [
                    "jumlah"            => $input['jumlah_p'][$i],
                    "pendidikan_id"     => $input['pendidikan_id'][$i],
                    "summary_id"        => $mSummary->id,
                ];
                // ------------------------------------------------------------
                SummarySAPendidikan::create($summaryPendidikan);
                // ------------------------------------------------------------
            }
            // ----------------------------------------------------------------
            return redirect()->route('import.summary.index')->with('success', __('label.SUCCESS_UPDATE_MESSAGE'));
            // ----------------------------------------------------------------
        } catch (\Throwable $th) {
            return redirect()->route('import.summary.index')->with('success', __('label.FAIL_UPDATE_MESSAGE'));
        }
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------
}
// ----------------------------------------------------------------------------