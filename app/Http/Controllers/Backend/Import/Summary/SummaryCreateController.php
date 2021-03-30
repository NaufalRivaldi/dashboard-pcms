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
class SummaryCreateController extends Controller
{
    // ------------------------------------------------------------------------
    public function index()
    {
        // --------------------------------------------------------------------
        $data = new \stdClass;
        $data->title            = "Summary - Form";
        $data->summary          = new Summary();
        // --------------------------------------------------------------------
        // Init data
        // --------------------------------------------------------------------
        $data->pageType = "create";

        if(Auth::user()->level_id != 1){
            $data->cabangs = Cabang::where('status', 1)->where('user_id', Auth::user()->id)->pluck('nama', 'id');
        }else{
            $data->cabangs = Cabang::where('status', 1)->pluck('nama', 'id');
        }
        // --------------------------------------------------------------------
        return view('backend.import.summary.add', (array) $data);
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
            $summary = Summary::where('bulan', $month)->where('tahun', $year)->where('cabang_id', $input['cabang_id'])->where('status', 1)->first();
            if(empty($summary)){
                // ------------------------------------------------------------
                // Check data for import data
                // ------------------------------------------------------------
                // LA03
                $pembayaran = Pembayaran::where('bulan', $month)->where('tahun', $year)->where('cabang_id', $input['cabang_id'])->first();
                if(!empty($pembayaran)) $data->la03 = true;
                else $data->la03 = false;

                // LA06
                $siswaAktif = SiswaAktif::where('bulan', $month)->where('tahun', $year)->where('cabang_id', $input['cabang_id'])->first();
                if(!empty($siswaAktif)) $data->la06 = true;
                else $data->la06 = false;

                // LA07
                $siswaAktifPendidikan = SiswaAktifPendidikan::where('bulan', $month)->where('tahun', $year)->where('cabang_id', $input['cabang_id'])->first();
                if(!empty($siswaAktifPendidikan)) $data->la07 = true;
                else $data->la07 = false;

                // LA09
                $siswaBaru = SiswaBaru::where('bulan', $month)->where('tahun', $year)->where('cabang_id', $input['cabang_id'])->first();
                if(!empty($siswaBaru)) $data->la09 = true;
                else $data->la09 = false;

                // LA12
                $siswaInaktif = SiswaInaktif::where('bulan', $month)->where('tahun', $year)->where('cabang_id', $input['cabang_id'])->first();
                if(!empty($siswaInaktif)) $data->la12 = true;
                else $data->la12 = false;

                // LA13
                $siswaCuti = SiswaCuti::where('bulan', $month)->where('tahun', $year)->where('cabang_id', $input['cabang_id'])->first();
                if(!empty($siswaCuti)) $data->la13 = true;
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
                    "siswa_baru"        => $siswaBaru->sum('jumlah'),
                    "siswa_cuti"        => $siswaCuti->sum('jumlah'),
                    "siswa_keluar"      => $siswaInaktif->sum('jumlah'),
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
                $mSummary->siswa_baru        = $siswaBaru->sum('jumlah');
                $mSummary->siswa_cuti        = $siswaCuti->sum('jumlah');
                $mSummary->siswa_keluar      = $siswaInaktif->sum('jumlah');
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
            // Delete all data import
            // ----------------------------------------------------------------
            // LA03
            $pembayaran->delete();

            // LA06
            $siswaAktif->delete();

            // LA07
            $siswaAktifPendidikan->delete();

            // LA09
            $siswaBaru->delete();

            // LA12
            $siswaInaktif->delete();

            // LA13
            $siswaCuti->delete();
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
        $data->title            = "LA12 - Edit Form";
        $data->siswaInaktif       = SiswaInaktif::find($id);
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
        return view('backend.import.la12.form', (array) $data);
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
            'id'                => 'required',
            'bulan_tahun'       => 'required',
            'cabang_id'         => 'required',
            'jumlah'            => 'required|numeric',
        ])->validate();
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Use try catch
        // --------------------------------------------------------------------
        try {
            // ----------------------------------------------------------------
            // Update siswa Inaktif
            // ----------------------------------------------------------------
            $input = $request->all();
            // ----------------------------------------------------------------
            $siswaInaktif = SiswaInaktif::find($input['id']);
            $siswaInaktif->bulan      = Carbon::parse('01 '.$input['bulan_tahun'])->format('m');
            $siswaInaktif->tahun      = Carbon::parse('01 '.$input['bulan_tahun'])->format('Y');
            $siswaInaktif->jumlah     = $input['jumlah'];
            $siswaInaktif->cabang_id  = $input['cabang_id'];
            $siswaInaktif->user_id    = Auth::user()->id;
            $siswaInaktif->save();
            // ----------------------------------------------------------------
            return redirect()->route('import.la12.index')->with('success', __('label.SUCCESS_UPDATE_MESSAGE'));
            // ----------------------------------------------------------------
        } catch (\Throwable $th) {
            return redirect()->route('import.la12.index')->with('success', __('label.FAIL_UPDATE_MESSAGE'));
        }
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------
}
// ----------------------------------------------------------------------------