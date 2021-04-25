<?php
// ----------------------------------------------------------------------------
namespace App\Http\Controllers\Backend\Report\Under5;
// ----------------------------------------------------------------------------
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// ----------------------------------------------------------------------------
use App\Models\Cabang;
use App\Models\Materi;
use App\Models\Pendidikan;
use App\Models\Wilayah;
use App\Models\SubWilayah;
use App\Models\Summary;
use App\Models\SummarySAMateri;
use App\Models\SummarySAPendidikan;
// ----------------------------------------------------------------------------
use Carbon\Carbon;
use Auth;
use DB;
use PDF;
// ----------------------------------------------------------------------------
class Under5Controller extends Controller
{
    // ------------------------------------------------------------------------
    public function index()
    {
        // --------------------------------------------------------------------
        $data = new \stdClass; $item = new \stdClass;
        $data->title        = "Report Under 5";
        $data->item         = $item;
        // --------------------------------------------------------------------
        // Init data
        // --------------------------------------------------------------------
        $item->periode = viewDate(Carbon::now()->firstOfMonth()->format('Y-m-d'));
        // --------------------------------------------------------------------
        $data->dataSetPenerimaan    = $this->getDataSetPenerimaan($item->periode);
        $data->dataSetRoyalti       = $this->getDataSetRoyalti($item->periode);
        $data->dataSetSiswaAktif    = $this->getDataSetSiswaAktif($item->periode);
        $data->dataSetSiswaAktifJurusan    = $this->getDataSetSiswaAktifJurusan($item->periode);
        $data->dataSetSiswaAktifPendidikan = $this->getDataSetSiswaAktifPendidikan($item->periode);
        // --------------------------------------------------------------------
        return view('backend.report.under5.index', (array) $data);
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    public function search(Request $request){
        // --------------------------------------------------------------------
        $data = new \stdClass; 
        // --------------------------------------------------------------------
        // Use try catch
        // --------------------------------------------------------------------
        try {
            // ----------------------------------------------------------------
            // Set filter date
            // ----------------------------------------------------------------
            $data->periode = $request->periodeBulan != null ? $request->periodeBulan : $request->periodeTahun;
            // ----------------------------------------------------------------
            $periodeBulan = $request->periodeBulan;
            $periodeTahun = $request->periodeTahun;
            // ----------------------------------------------------------------
            $data->dataSetPenerimaan    = $this->getDataSetPenerimaan($periodeBulan, $periodeTahun);
            $data->dataSetRoyalti       = $this->getDataSetRoyalti($periodeBulan, $periodeTahun);
            $data->dataSetSiswaAktif    = $this->getDataSetSiswaAktif($periodeBulan, $periodeTahun);
            $data->dataSetSiswaAktifJurusan    = $this->getDataSetSiswaAktifJurusan($periodeBulan, $periodeTahun);
            $data->dataSetSiswaAktifPendidikan = $this->getDataSetSiswaAktifPendidikan($periodeBulan, $periodeTahun);
            // ----------------------------------------------------------------
            $data->status = true;
            $data->message = "Filter data berhasil";
            return response()->json($data);
            // ----------------------------------------------------------------
        } catch (\Throwable $th) {
            $data->status = false;
            $data->message = "Filter data gagal";
            return response()->json($data);
        }
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    public function export(Request $request){
        // --------------------------------------------------------------------
        $data = new \stdClass;
        // --------------------------------------------------------------------
        // Use try catch
        // --------------------------------------------------------------------
        // Set filter date
        // ----------------------------------------------------------------
        if($request->periode_bulan != "undefined"){
            $periodeBulan = $request->periode_bulan;
        }else{
            $periodeBulan = null;
        }
        // ----------------------------------------------------------------
        // Set filter year
        // ----------------------------------------------------------------
        if($request->periode_tahun != "undefined"){
            $periodeTahun = $request->periode_tahun;
        }else{
            $periodeTahun = null;
        }
        // ----------------------------------------------------------------
        $data->dataSetPenerimaan    = $this->getDataSetPenerimaan($periodeBulan, $periodeTahun);
        $data->dataSetRoyalti       = $this->getDataSetRoyalti($periodeBulan, $periodeTahun);
        $data->dataSetSiswaAktif    = $this->getDataSetSiswaAktif($periodeBulan, $periodeTahun);
        $data->dataSetSiswaAktifJurusan    = $this->getDataSetSiswaAktifJurusan($periodeBulan, $periodeTahun);
        $data->dataSetSiswaAktifPendidikan = $this->getDataSetSiswaAktifPendidikan($periodeBulan, $periodeTahun);
        // ----------------------------------------------------------------
        $data->periode = $request->periode_bulan != "undefined" ? $request->periode_bulan : $request->periode_tahun;
        // ----------------------------------------------------------------
        return view('pdf.report.under5', (array) $data);
    }

    // ------------------------------------------------------------------------
    // Get data penerimaan summery (Chart data)
    // ------------------------------------------------------------------------
    public function getDataSetPenerimaan($periode, $year = null){
        // --------------------------------------------------------------------
        // Set periode
        // --------------------------------------------------------------------
        if($periode != null){
            $month  = Carbon::parse('01 '.$periode)->format('m');
            $year   = Carbon::parse('01 '.$periode)->format('Y');

            $query = Summary::selectRaw('uang_pendaftaran, uang_kursus, uang_pendaftaran + uang_kursus as total, cabang_id')->where('status', 1);
        }else{
            $query = Summary::selectRaw('SUM(uang_pendaftaran) as uang_pendaftaran, SUM(uang_kursus) as uang_kursus, SUM(uang_pendaftaran + uang_kursus) as total, tahun, cabang_id')->where('status', 1);
        }
        // --------------------------------------------------------------------
        // Where Cabang is active
        // --------------------------------------------------------------------
        if(Auth::user()->level_id == 2){
            $query->whereHas('cabang', function($query){
                $query->where('user_id', Auth::user()->id);
                $query->where('status', 1);
            });
        }else{
            $query->whereHas('cabang', function($query){
                $query->where('status', 1);
            });
        }
        // --------------------------------------------------------------------
        // Where Month
        // --------------------------------------------------------------------
        if($periode != null){
            $query->where(function($query)use($month){
                $query->where('bulan', $month);
            });
        }
        // --------------------------------------------------------------------
        // Where Year
        // --------------------------------------------------------------------
        $query->where('tahun', $year);
        // --------------------------------------------------------------------
        // Order by data
        // --------------------------------------------------------------------
        $query->orderBy('total', 'asc');
        // --------------------------------------------------------------------
        // Group by data
        // --------------------------------------------------------------------
        if($periode == null){
            $query->groupBy('tahun', 'cabang_id');
        }
        // --------------------------------------------------------------------
        // Set label
        // --------------------------------------------------------------------
        $labels = [];
        $under5   = $query->limit(5)->pluck('cabang_id')->toArray();
        for($i = 0; $i < count($under5); $i++){
            $cabang     = Cabang::find($under5[$i]);
            $labels[]   = $cabang->nama;
        }
        // --------------------------------------------------------------------
        $totalPendaftaran = [];
        $totalKursus = [];
        $totalPenerimaan = [];
        // --------------------------------------------------------------------
        $result = $query->limit(5)->get();
        foreach($result as $row){
            $totalPendaftaran[] = (int)$row->uang_pendaftaran;
            $totalKursus[]      = (int)$row->uang_kursus;
            $totalPenerimaan[]  = (int)$row->total;
        }
        // --------------------------------------------------------------------
        // Result
        // --------------------------------------------------------------------
        $result = [
            [
                'label'             => 'Total Penerimaan',
                'backgroundColor'   => '#f39c12',
                'data'              => $totalPenerimaan,
                'type'              => 'line',
                'fill'              => false,
                'borderColor'       => '#f39c12',
                'tension'           => 0,
            ],
            [
                'label'             => 'Uang Pendaftaran',
                'backgroundColor'   => '#3498db',
                'data'              => $totalPendaftaran,
                'stack'             => 'stack_1'
            ],
            [
                'label'             => 'Uang Kursus',
                'backgroundColor'   => '#1abc9c',
                'data'              => $totalKursus,
                'stack'             => 'stack_1'
            ],
        ];
        // --------------------------------------------------------------------
        return [
            'labels'    => $labels,
            'result'    => $result,
        ];
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    // Get data royalti summery (Chart data)
    // ------------------------------------------------------------------------
    public function getDataSetRoyalti($periode, $year = null){
        // --------------------------------------------------------------------
        // Set periode
        // --------------------------------------------------------------------
        if($periode != null){
            $month  = Carbon::parse('01 '.$periode)->format('m');
            $year   = Carbon::parse('01 '.$periode)->format('Y');

            $query = Summary::selectRaw('uang_pendaftaran, uang_kursus, uang_pendaftaran + uang_kursus as total, cabang_id')->where('status', 1);
        }else{
            $query = Summary::selectRaw('SUM(uang_pendaftaran) as uang_pendaftaran, SUM(uang_kursus) as uang_kursus, SUM(uang_pendaftaran + uang_kursus) as total, tahun, cabang_id')->where('status', 1);
        }
        // --------------------------------------------------------------------
        // Where Cabang is active
        // --------------------------------------------------------------------
        if(Auth::user()->level_id == 2){
            $query->whereHas('cabang', function($query){
                $query->where('user_id', Auth::user()->id);
                $query->where('status', 1);
            });
        }else{
            $query->whereHas('cabang', function($query){
                $query->where('status', 1);
            });
        }
        // --------------------------------------------------------------------
        // Where Month
        // --------------------------------------------------------------------
        if($periode != null){
            $query->where(function($query)use($month){
                $query->where('bulan', $month);
            });
        }
        // --------------------------------------------------------------------
        // Where Year
        // --------------------------------------------------------------------
        $query->where('tahun', $year);
        // --------------------------------------------------------------------
        // Order by data
        // --------------------------------------------------------------------
        $query->orderBy('total', 'asc');
        // --------------------------------------------------------------------
        // Group by data
        // --------------------------------------------------------------------
        if($periode == null){
            $query->groupBy('tahun', 'cabang_id');
        }
        // --------------------------------------------------------------------
        // Set label
        // --------------------------------------------------------------------
        $labels = [];
        $under5   = $query->limit(5)->pluck('cabang_id')->toArray();
        for($i = 0; $i < count($under5); $i++){
            $cabang     = Cabang::find($under5[$i]);
            $labels[]   = $cabang->nama;
        }
        // --------------------------------------------------------------------
        $totalRoyalti = [];
        // --------------------------------------------------------------------
        $result = $query->limit(5)->get();
        foreach($result as $row){
            $totalRoyalti[] = (int)$row->total * 0.1;
        }
        // --------------------------------------------------------------------
        // Result
        // --------------------------------------------------------------------
        $result = [
            [
                'label'             => 'Royalti',
                'backgroundColor'   => '#74b9ff',
                'data'              => $totalRoyalti,
                'type'              => 'line',
                'tension'           => 0,
            ],
        ];
        // --------------------------------------------------------------------
        return [
            'labels'    => $labels,
            'result'    => $result,
        ];
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    // Get data siswa aktif summery (Chart data)
    // ------------------------------------------------------------------------
    public function getDataSetSiswaAktif($periode, $year = null){
        // --------------------------------------------------------------------
        // Set periode
        // --------------------------------------------------------------------
        if($periode != null){
            $month  = Carbon::parse('01 '.$periode)->format('m');
            $year   = Carbon::parse('01 '.$periode)->format('Y');

            $query = Summary::selectRaw('siswa_aktif, siswa_baru, siswa_cuti, siswa_keluar, siswa_aktif + siswa_baru + siswa_cuti + siswa_keluar as total, cabang_id')->where('status', 1);
        }else{
            $query = Summary::selectRaw('SUM(siswa_aktif) as siswa_aktif, SUM(siswa_baru) as siswa_baru, SUM(siswa_cuti) as siswa_cuti, SUM(siswa_keluar) as siswa_keluar, SUM(siswa_aktif + siswa_baru + siswa_cuti + siswa_keluar) as total, tahun, cabang_id')->where('status', 1);
        }
        // --------------------------------------------------------------------
        // Where Cabang is active
        // --------------------------------------------------------------------
        if(Auth::user()->level_id == 2){
            $query->whereHas('cabang', function($query){
                $query->where('user_id', Auth::user()->id);
                $query->where('status', 1);
            });
        }else{
            $query->whereHas('cabang', function($query){
                $query->where('status', 1);
            });
        }
        // --------------------------------------------------------------------
        // Where Month
        // --------------------------------------------------------------------
        if($periode != null){
            $query->where(function($query)use($month){
                $query->where('bulan', $month);
            });
        }
        // --------------------------------------------------------------------
        // Where Year
        // --------------------------------------------------------------------
        $query->where('tahun', $year);
        // --------------------------------------------------------------------
        // Order by data
        // --------------------------------------------------------------------
        $query->orderBy('total', 'asc');
        // --------------------------------------------------------------------
        // Group by data
        // --------------------------------------------------------------------
        if($periode == null){
            $query->groupBy('tahun', 'cabang_id');
        }
        // --------------------------------------------------------------------
        // Set label
        // --------------------------------------------------------------------
        $labels = [];
        $under5   = $query->limit(5)->pluck('cabang_id')->toArray();
        for($i = 0; $i < count($under5); $i++){
            $cabang     = Cabang::find($under5[$i]);
            $labels[]   = $cabang->nama;
        }
        // --------------------------------------------------------------------
        $totalSiswaAktif = [];
        $totalSiswaBaru = [];
        $totalSiswaCuti = [];
        $totalSiswaKeluar = [];
        // --------------------------------------------------------------------
        $result = $query->limit(5)->get();
        foreach($result as $row){
            $totalSiswaAktif[]  = (int)$row->siswa_aktif;
            $totalSiswaBaru[]   = (int)$row->siswa_baru;
            $totalSiswaCuti[]   = (int)$row->siswa_cuti;
            $totalSiswaKeluar[] = (int)$row->siswa_keluar;
        }
        // --------------------------------------------------------------------
        // Result
        // --------------------------------------------------------------------
        $result = [
            [
                'label'             => 'Siswa Aktif',
                'backgroundColor'   => '#3498db',
                'data'              => $totalSiswaAktif,
                'stack'             => 'stack_1'
            ],
            [
                'label'             => 'Siswa Baru',
                'backgroundColor'   => '#d35400',
                'data'              => $totalSiswaBaru,
                'stack'             => 'stack_1'
            ],
            [
                'label'             => 'Siswa Cuti',
                'backgroundColor'   => '#27ae60',
                'data'              => $totalSiswaCuti,
                'stack'             => 'stack_1'
            ],
            [
                'label'             => 'Siswa Keluar',
                'backgroundColor'   => '#8e44ad',
                'data'              => $totalSiswaKeluar,
                'stack'             => 'stack_1'
            ],
        ];
        // --------------------------------------------------------------------
        return [
            'labels'    => $labels,
            'result'    => $result,
        ];
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    // Get data siswa aktif jurusan summery (Chart data)
    // ------------------------------------------------------------------------
    public function getDataSetSiswaAktifJurusan($periode, $year = null){
        // --------------------------------------------------------------------
        $cabangColl = Cabang::all();
        // --------------------------------------------------------------------
        // Set periode
        // --------------------------------------------------------------------
        if($periode != null){
            $month  = Carbon::parse('01 '.$periode)->format('m');
            $year   = Carbon::parse('01 '.$periode)->format('Y');

            $query = Summary::where('status', 1);
        }else{
            $query = Summary::where('status', 1);
        }
        // --------------------------------------------------------------------
        // Where Cabang is active
        // --------------------------------------------------------------------
        if(Auth::user()->level_id == 2){
            $query->whereHas('cabang', function($query){
                $query->where('user_id', Auth::user()->id);
                $query->where('status', 1);
            });
        }else{
            $query->whereHas('cabang', function($query){
                $query->where('status', 1);
            });
        }
        // --------------------------------------------------------------------
        // Where Month
        // --------------------------------------------------------------------
        if($periode != null){
            $query->where(function($query)use($month){
                $query->where('bulan', $month);
            });
        }
        // --------------------------------------------------------------------
        // Where Year
        // --------------------------------------------------------------------
        $query->where('tahun', $year);
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Get data summary sa jurusan
        // --------------------------------------------------------------------
        $summaryArray = $query->pluck('id')->toArray();
        // --------------------------------------------------------------------
        $relationship = DB::table('summary_sa_materi')
                        ->selectRaw('SUM(summary_sa_materi.jumlah) as total_jumlah, summary.cabang_id')
                        ->join('summary', 'summary_sa_materi.summary_id', '=', 'summary.id')
                        ->join('materi', 'summary_sa_materi.materi_id', '=', 'materi.id')
                        ->whereIn('summary_sa_materi.summary_id', $summaryArray)
                        ->orderBy('total_jumlah', 'asc')
                        ->groupBy('summary.cabang_id');
        // --------------------------------------------------------------------
        // Set label
        // --------------------------------------------------------------------
        $labels = [];
        $under5   = $relationship->limit(5)->pluck('cabang_id')->toArray();
        for($i = 0; $i < count($under5); $i++){
            $cabang     = Cabang::find($under5[$i]);
            $labels[]   = $cabang->nama;
        }
        // --------------------------------------------------------------------
        $relationship = DB::table('summary_sa_materi')
                        ->selectRaw('SUM(summary_sa_materi.jumlah) as total_jumlah, materi.id as materi_id, summary.cabang_id')
                        ->join('summary', 'summary_sa_materi.summary_id', '=', 'summary.id')
                        ->join('materi', 'summary_sa_materi.materi_id', '=', 'materi.id')
                        ->whereIn('summary_sa_materi.summary_id', $summaryArray)
                        ->orderBy('total_jumlah', 'asc')
                        ->groupBy('summary.cabang_id', 'materi.id');
        // --------------------------------------------------------------------
        // Set array dataset
        // --------------------------------------------------------------------
        $data = $relationship->get();
        // --------------------------------------------------------------------
        $result = [];
        $materis = Materi::where('status', 1)->get();
        foreach($materis as $materi){
            $total = [];
            // --------------------------------------------------------------------
            for($i = 0; $i < count($labels); $i++){
                foreach($data as $row){
                    $location = $cabangColl->where('id', $row->cabang_id)->where('nama', $labels[$i])->first();
                    if($row->materi_id == $materi->id && $location != null){
                        $total[] = (int)$row->total_jumlah;
                    }
                }
            }
            
            $result[] = [
                'label'             => $materi->nama,
                'backgroundColor'   => randomColor(),
                'data'              => $total,
                'stack'             => 'stack_1',
            ];
        }
        // --------------------------------------------------------------------
        return [
            'labels'    => $labels,
            'result'    => $result,
        ];
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    // Get data siswa aktif pendidikan summery (Chart data)
    // ------------------------------------------------------------------------
    public function getDataSetSiswaAktifpendidikan($periode, $year = null){
        // --------------------------------------------------------------------
        $cabangColl = Cabang::all();
        // --------------------------------------------------------------------
        // Set periode
        // --------------------------------------------------------------------
        if($periode != null){
            $month  = Carbon::parse('01 '.$periode)->format('m');
            $year   = Carbon::parse('01 '.$periode)->format('Y');

            $query = Summary::where('status', 1);
        }else{
            $query = Summary::where('status', 1);
        }
        // --------------------------------------------------------------------
        // Where Cabang is active
        // --------------------------------------------------------------------
        if(Auth::user()->level_id == 2){
            $query->whereHas('cabang', function($query){
                $query->where('user_id', Auth::user()->id);
                $query->where('status', 1);
            });
        }else{
            $query->whereHas('cabang', function($query){
                $query->where('status', 1);
            });
        }
        // --------------------------------------------------------------------
        // Where Month
        // --------------------------------------------------------------------
        if($periode != null){
            $query->where(function($query)use($month){
                $query->where('bulan', $month);
            });
        }
        // --------------------------------------------------------------------
        // Where Year
        // --------------------------------------------------------------------
        $query->where('tahun', $year);
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Get data summary sa jurusan
        // --------------------------------------------------------------------
        $summaryArray = $query->pluck('id')->toArray();
        // --------------------------------------------------------------------
        $relationship = DB::table('summary_sa_pendidikan')
                        ->selectRaw('SUM(summary_sa_pendidikan.jumlah) as total_jumlah, summary.cabang_id')
                        ->join('summary', 'summary_sa_pendidikan.summary_id', '=', 'summary.id')
                        ->join('pendidikan', 'summary_sa_pendidikan.pendidikan_id', '=', 'pendidikan.id')
                        ->whereIn('summary_sa_pendidikan.summary_id', $summaryArray)
                        ->orderBy('total_jumlah', 'asc')
                        ->groupBy('summary.cabang_id');
        // --------------------------------------------------------------------
        // Set label
        // --------------------------------------------------------------------
        $labels = [];
        $under5   = $relationship->limit(5)->pluck('cabang_id')->toArray();
        for($i = 0; $i < count($under5); $i++){
            $cabang     = Cabang::find($under5[$i]);
            $labels[]   = $cabang->nama;
        }
        // --------------------------------------------------------------------
        $relationship = DB::table('summary_sa_pendidikan')
                        ->selectRaw('SUM(summary_sa_pendidikan.jumlah) as total_jumlah, pendidikan.id as pendidikan_id, summary.cabang_id')
                        ->join('summary', 'summary_sa_pendidikan.summary_id', '=', 'summary.id')
                        ->join('pendidikan', 'summary_sa_pendidikan.pendidikan_id', '=', 'pendidikan.id')
                        ->whereIn('summary_sa_pendidikan.summary_id', $summaryArray)
                        ->orderBy('total_jumlah', 'asc')
                        ->groupBy('summary.cabang_id', 'pendidikan.id');
        // --------------------------------------------------------------------
        // Set array dataset
        // --------------------------------------------------------------------
        $data = $relationship->get();
        // --------------------------------------------------------------------
        $result = [];
        $pendidikans = Pendidikan::all();
        foreach($pendidikans as $pendidikan){
            $total = [];
            // --------------------------------------------------------------------
            for($i = 0; $i < count($labels); $i++){
                foreach($data as $row){
                    $location = $cabangColl->where('id', $row->cabang_id)->where('nama', $labels[$i])->first();
                    if($row->pendidikan_id == $pendidikan->id && $location != null){
                        $total[] = (int)$row->total_jumlah;
                    }
                }
            }
            
            $result[] = [
                'label'             => $pendidikan->nama,
                'backgroundColor'   => randomColor(),
                'data'              => $total,
                'stack'             => 'stack_1',
            ];
        }
        // --------------------------------------------------------------------
        return [
            'labels'    => $labels,
            'result'    => $result,
        ];
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------
}

