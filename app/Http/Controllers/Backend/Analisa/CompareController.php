<?php
// ----------------------------------------------------------------------------
namespace App\Http\Controllers\Backend\Analisa;
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
class CompareController extends Controller
{
    // ------------------------------------------------------------------------
    public function index()
    {
        // --------------------------------------------------------------------
        $data = new \stdClass; $item = new \stdClass;
        $data->title    = "Compare Data";
        $data->item     = $item;
        // --------------------------------------------------------------------
        // Init data
        // --------------------------------------------------------------------
        $filterDate[0] = viewDate(Carbon::now()->firstOfMonth()->subYears(1)->addMonth(1)->format('Y-m-d'));
        $filterDate[1] = viewDate(Carbon::now()->firstOfMonth()->format('Y-m-d'));
        // --------------------------------------------------------------------
        $item->filterDate = $filterDate;
        // --------------------------------------------------------------------
        $year = Carbon::now()->format('Y');
        $yearArray[0] = Carbon::now()->format('Y');
        $yearArray[1] = Carbon::now()->format('Y');
        // --------------------------------------------------------------------
        // If admin
        // --------------------------------------------------------------------
        if(Auth::user()->level_id == 1){
            $data->cabangs              = Cabang::where('status', 1)->pluck('nama', 'id');
            $data->wilayahs             = Wilayah::where('status', 1)->pluck('nama', 'id');
            $data->subWilayahs          = SubWilayah::where('status', 1)->pluck('nama', 'id');
        }
        // --------------------------------------------------------------------
        // if owner
        // --------------------------------------------------------------------
        if(Auth::user()->level_id == 2){
            $data->cabangs              = Cabang::where('user_id', Auth::user()->id)->where('status', 1)->pluck('nama', 'id');
            $data->wilayahs             = Wilayah::whereHas('cabangs', function($query){
                                                $query->where('user_id', Auth::user()->id);
                                            })->where('status', 1)->pluck('nama', 'id');
            $data->subWilayahs          = SubWilayah::whereHas('cabangs', function($query){
                                                $query->where('user_id', Auth::user()->id);
                                            })->where('status', 1)->pluck('nama', 'id');
        }
        // --------------------------------------------------------------------
        $data->labels               = $this->getLabel($item->filterDate, null);
        // --------------------------------------------------------------------
        return view('backend.analisa.compare.index', (array) $data);
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    public function search(Request $request){
        // --------------------------------------------------------------------
        $data = new \stdClass;
        // --------------------------------------------------------------------
        $startDate  = convertDate($request->startDate);
        $endDate    = convertDate($request->endDate);
        // --------------------------------------------------------------------
        if($startDate > $endDate){
            $data->status = false;
            $data->message = "Tanggal awal melebihi tanggal akhir!";
            return response()->json($data);
        }
        // --------------------------------------------------------------------
        if($request->startYear > $request->endYear){
            $data->status = false;
            $data->message = "Tahun awal melebihi tahun akhir!";
            return response()->json($data);
        }
        // --------------------------------------------------------------------
        // Use try catch
        // --------------------------------------------------------------------
        try {
            // ----------------------------------------------------------------
            // Set filter date
            // ----------------------------------------------------------------
            if($request->startDate != null && $request->endDate != null){
                $filterDate[0] = $request->startDate;
                $filterDate[1] = $request->endDate;
            }else{
                $filterDate = null;
            }
            // ----------------------------------------------------------------
            // Set filter year
            // ----------------------------------------------------------------
            if($request->startYear != null && $request->endYear != null){
                $filterYear[0] = $request->startYear;
                $filterYear[1] = $request->endYear;
            }else{
                $filterYear = null;
            }
            // ----------------------------------------------------------------
            // Set cabang_array
            // ----------------------------------------------------------------
            if($request->cabang_id_1 != null && $request->cabang_id_2 != null){
                $cabangArray[0] = $request->cabang_id_1;
                $cabangArray[1] = $request->cabang_id_2;
            }else{
                $cabangArray = null;
            }
            // ----------------------------------------------------------------
            // Set wilayah_array
            // ----------------------------------------------------------------
            if($request->wilayah_id_1 != null && $request->wilayah_id_2 != null){
                $wilayahArray[0] = $request->wilayah_id_1;
                $wilayahArray[1] = $request->wilayah_id_2;
            }else{
                $wilayahArray = null;
            }
            // ----------------------------------------------------------------
            // Set sub_wilayah_array
            // ----------------------------------------------------------------
            if($request->sub_wilayah_id_1 != null && $request->sub_wilayah_id_2 != null){
                $subWilayahArray[0] = $request->sub_wilayah_id_1;
                $subWilayahArray[1] = $request->sub_wilayah_id_2;
            }else{
                $subWilayahArray = null;
            }
            // ----------------------------------------------------------------
            // Checking data filtering
            // ----------------------------------------------------------------
            if($cabangArray == null && $wilayahArray == null && $subWilayahArray == null){
                $data->status = false;
                $data->message = "Tidak ada data yang di compare, silahkan isi cabang, wilayah, atau sub wilayah yang akan di compare.";
                return response()->json($data);
            }
            // ----------------------------------------------------------------
            $data->labels               = $this->getLabel($filterDate, $filterYear, $cabangArray, $wilayahArray, $subWilayahArray);
            $data->dataSetPenerimaan    = $this->getDataSetPenerimaan($filterDate, $cabangArray, $wilayahArray, $subWilayahArray, $filterYear);
            $data->dataSetRoyalti       = $this->getDataSetRoyalti($filterDate, $cabangArray, $wilayahArray, $subWilayahArray, $filterYear);
            $data->dataSetSiswaAktif    = $this->getDataSetSiswaAktif($filterDate, $cabangArray, $wilayahArray, $subWilayahArray, $filterYear);
            $data->dataSetSiswaAktifJurusan    = $this->getDataSetSiswaAktifJurusan($filterDate, $cabangArray, $wilayahArray, $subWilayahArray, $filterYear);
            $data->dataSetSiswaAktifPendidikan = $this->getDataSetSiswaAktifPendidikan($filterDate, $cabangArray, $wilayahArray, $subWilayahArray, $filterYear);
            // ----------------------------------------------------------------
            $data->status = true;
            if($cabangArray != null){
                $data->cabang[0] = strtoupper(Cabang::find($request->cabang_id_1)->nama);
                $data->cabang[1] = strtoupper(Cabang::find($request->cabang_id_2)->nama);
            }else{
                $data->cabang = null;
            }

            if($wilayahArray != null){
                $data->wilayah[0] = strtoupper(Wilayah::find($request->wilayah_id_1)->nama);
                $data->wilayah[1] = strtoupper(Wilayah::find($request->wilayah_id_2)->nama);
            }else{
                $data->wilayah = null;
            }

            if($subWilayahArray != null){
                $data->sub_wilayah[0] = strtoupper(SubWilayah::find($request->sub_wilayah_id_1)->nama);
                $data->sub_wilayah[1] = strtoupper(SubWilayah::find($request->sub_wilayah_id_2)->nama);
            }else{
                $data->sub_wilayah = null;
            }
            
            $data->message = "Filter data berhasil";
            return response()->json($data);
            // ----------------------------------------------------------------
        } catch (\Throwable $th) {
            dd($th);
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
        // ----------------------------------------------------------------
        // Set filter date
        // ----------------------------------------------------------------
        if($request->start_date != "undefined" && $request->end_date != "undefined"){
            $filterDate[0] = $request->start_date;
            $filterDate[1] = $request->end_date;
        }else{
            $filterDate = null;
        }
        // ----------------------------------------------------------------
        // Set filter year
        // ----------------------------------------------------------------
        if($request->start_year != "undefined" && $request->end_year != "undefined"){
            $filterYear[0] = $request->start_year;
            $filterYear[1] = $request->end_year;
        }else{
            $filterYear = null;
        }
        // ----------------------------------------------------------------
        // Set cabang_array
        // ----------------------------------------------------------------
        if($request->cabang_id_1 != null && $request->cabang_id_2 != null){
            $cabangArray[0] = $request->cabang_id_1;
            $cabangArray[1] = $request->cabang_id_2;
        }else{
            $cabangArray = null;
        }
        // ----------------------------------------------------------------
        // Set wilayah_array
        // ----------------------------------------------------------------
        if($request->wilayah_id_1 != null && $request->wilayah_id_2 != null){
            $wilayahArray[0] = $request->wilayah_id_1;
            $wilayahArray[1] = $request->wilayah_id_2;
        }else{
            $wilayahArray = null;
        }
        // ----------------------------------------------------------------
        // Set sub_wilayah_array
        // ----------------------------------------------------------------
        if($request->sub_wilayah_id_1 != null && $request->sub_wilayah_id_2 != null){
            $subWilayahArray[0] = $request->sub_wilayah_id_1;
            $subWilayahArray[1] = $request->sub_wilayah_id_2;
        }else{
            $subWilayahArray = null;
        }
        // ----------------------------------------------------------------
        // Checking data filtering
        // ----------------------------------------------------------------
        if($cabangArray == null && $wilayahArray == null && $subWilayahArray == null){
            $data->status = false;
            $data->message = "Tidak ada data yang di compare, silahkan isi cabang, wilayah, atau sub wilayah yang akan di compare.";
            return response()->json($data);
        }
        // ----------------------------------------------------------------
        $data->labels               = $this->getLabel($filterDate, $filterYear, $cabangArray, $wilayahArray, $subWilayahArray);
        $data->dataSetPenerimaan    = $this->getDataSetPenerimaan($filterDate, $cabangArray, $wilayahArray, $subWilayahArray, $filterYear);
        $data->dataSetRoyalti       = $this->getDataSetRoyalti($filterDate, $cabangArray, $wilayahArray, $subWilayahArray, $filterYear);
        $data->dataSetSiswaAktif    = $this->getDataSetSiswaAktif($filterDate, $cabangArray, $wilayahArray, $subWilayahArray, $filterYear);
        $data->dataSetSiswaAktifJurusan    = $this->getDataSetSiswaAktifJurusan($filterDate, $cabangArray, $wilayahArray, $subWilayahArray, $filterYear);
        $data->dataSetSiswaAktifPendidikan = $this->getDataSetSiswaAktifPendidikan($filterDate, $cabangArray, $wilayahArray, $subWilayahArray, $filterYear);
        // ----------------------------------------------------------------
        $data->status = true;
        if($cabangArray != null){
            $data->cabang[0] = strtoupper(Cabang::find($request->cabang_id_1)->nama);
            $data->cabang[1] = strtoupper(Cabang::find($request->cabang_id_2)->nama);
        }else{
            $data->cabang = null;
        }

        if($wilayahArray != null){
            $data->wilayah[0] = strtoupper(Wilayah::find($request->wilayah_id_1)->nama);
            $data->wilayah[1] = strtoupper(Wilayah::find($request->wilayah_id_2)->nama);
        }else{
            $data->wilayah = null;
        }

        if($subWilayahArray != null){
            $data->sub_wilayah[0] = strtoupper(SubWilayah::find($request->sub_wilayah_id_1)->nama);
            $data->sub_wilayah[1] = strtoupper(SubWilayah::find($request->sub_wilayah_id_2)->nama);
        }else{
            $data->sub_wilayah = null;
        }
        // ----------------------------------------------------------------
        return view('pdf.compare-export', (array) $data);
    }

    // ------------------------------------------------------------------------
    public function getLabel($filterDate = null, $filterYear = null, $cabangArray = null, $wilayahArray = null, $subWilayahArray = null){
        // --------------------------------------------------------------------
        $labels = []; $firstLocation = null; $secondLocation = null;
        // --------------------------------------------------------------------
        // Set location on label
        // --------------------------------------------------------------------
        // If cabang is not null
        // --------------------------------------------------------------------
        if($cabangArray != null){
            $firstLocation    = $cabangArray != null ? Cabang::find($cabangArray[0]) : null;
            $secondLocation   = $cabangArray != null ? Cabang::find($cabangArray[1]) : null;
        }
        // --------------------------------------------------------------------
        // If wilayah is not null
        // --------------------------------------------------------------------
        if($wilayahArray != null){
            $firstLocation    = $wilayahArray != null ? Wilayah::find($wilayahArray[0]) : null;
            $secondLocation   = $wilayahArray != null ? Wilayah::find($wilayahArray[1]) : null;
        }
        // --------------------------------------------------------------------
        // If sub wilayah is not null
        // --------------------------------------------------------------------
        if($subWilayahArray != null){
            $firstLocation    = $subWilayahArray != null ? SubWilayah::find($subWilayahArray[0]) : null;
            $secondLocation   = $subWilayahArray != null ? SubWilayah::find($subWilayahArray[1]) : null;
        }
        // --------------------------------------------------------------------
        if($filterDate != null){
            // ----------------------------------------------------------------
            $firstYear  = Carbon::parse('01 '.$filterDate[0])->format('Y');
            $secondYear = Carbon::parse('01 '.$filterDate[1])->format('Y');
            // ----------------------------------------------------------------
            if($firstYear == $secondYear){
                // ------------------------------------------------------------
                $firstMonth     = Carbon::parse('01 '.$filterDate[0])->format('m');
                $secondMonth    = Carbon::parse('01 '.$filterDate[1])->format('m');
                // ------------------------------------------------------------
                for($i = $firstMonth - 1; $i < $secondMonth; $i++){
                    $month = strlen($i) == 1 ? "0".$i+1 : $i+1;
                    $labels[] = (Carbon::parse($firstYear.'-'.$month.'-01')->format('M Y')).' / '.($firstLocation ? $firstLocation->nama : '-');
                    $labels[] = (Carbon::parse($firstYear.'-'.$month.'-01')->format('M Y')).' / '.($secondLocation ? $secondLocation->nama : '-');
                }
                // ------------------------------------------------------------
            }elseif($firstYear < $secondYear){
                // ------------------------------------------------------------
                $firstMonth     = Carbon::parse('01 '.$filterDate[0])->format('m');
                $secondMonth    = Carbon::parse('01 '.$filterDate[0])->endOfYear()->format('m');
                // ------------------------------------------------------------
                for($i = $firstMonth - 1; $i < $secondMonth; $i++){
                    $month = strlen($i) == 1 ? "0".$i+1 : $i+1;
                    $labels[] = (Carbon::parse($firstYear.'-'.$month.'-01')->format('M Y')).' / '.($firstLocation ? $firstLocation->nama : '-');
                    $labels[] = (Carbon::parse($firstYear.'-'.$month.'-01')->format('M Y')).' / '.($secondLocation ? $secondLocation->nama : '-');
                }
                // ------------------------------------------------------------
                $firstMonth     = Carbon::parse('01 '.$filterDate[1])->startOfYear()->format('m');
                $secondMonth    = Carbon::parse('01 '.$filterDate[1])->format('m');
                // ------------------------------------------------------------
                for($i = $firstMonth - 1; $i < $secondMonth; $i++){
                    $month = strlen($i) == 1 ? "0".$i+1 : $i+1;
                    $labels[] = (Carbon::parse($secondYear.'-'.$month.'-01')->format('M Y')).' / '.($firstLocation ? $firstLocation->nama : '-');
                    $labels[] = (Carbon::parse($secondYear.'-'.$month.'-01')->format('M Y')).' / '.($secondLocation ? $secondLocation->nama : '-');
                }
                // ------------------------------------------------------------
            }
            // ----------------------------------------------------------------
        }else if($filterYear != null){
            for($i = $filterYear[0]; $i <= $filterYear[1]; $i++){
                $labels[] = $i.' / '.($firstLocation ? $firstLocation->nama : '-');
                $labels[] = $i.' / '.($secondLocation ? $secondLocation->nama : '-');
            }
        }
        // --------------------------------------------------------------------
        return $labels;
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    // Get data penerimaan summery (Chart data)
    // ------------------------------------------------------------------------
    public function getDataSetPenerimaan($filterDate, $cabang = null, $wilayah = null, $subWilayah = null, $filterYear = null){
        // --------------------------------------------------------------------
        // init data collection for set data on array
        // --------------------------------------------------------------------
        $cabangColl = Cabang::all();
        $wilayahColl = Wilayah::all();
        $subWilayahColl = SubWilayah::all();
        // --------------------------------------------------------------------
        // Make condition if filter with date or year
        // --------------------------------------------------------------------
        if($filterDate != null){
            $month = $this->setMonth($filterDate);
            // ----------------------------------------------------------------
            $year[0] = Carbon::parse('01 '.$filterDate[0])->format('Y');
            $year[1] = Carbon::parse('01 '.$filterDate[1])->format('Y');
            // ----------------------------------------------------------------
            // If cabang not null
            // ----------------------------------------------------------------
            if($cabang != null) $query = Summary::selectRaw('bulan, tahun, SUM(uang_pendaftaran) as total_up, SUM(uang_kursus) as total_k, cabang_id')->where('status', 1);
            // ----------------------------------------------------------------
            // If wilayah not null
            // ----------------------------------------------------------------
            if($wilayah != null){
                $query = DB::table('summary')
                            ->selectRaw('summary.bulan, summary.tahun, SUM(summary.uang_pendaftaran) as total_up, SUM(summary.uang_kursus) as total_k, cabang.wilayah_id')
                            ->join('cabang', 'cabang.id', '=', 'summary.cabang_id')
                            ->where('summary.status', 1);
            }
            // ----------------------------------------------------------------
            // If cabang not null
            // ----------------------------------------------------------------
            if($subWilayah != null){
                $query = DB::table('summary')
                            ->selectRaw('summary.bulan, summary.tahun, SUM(summary.uang_pendaftaran) as total_up, SUM(summary.uang_kursus) as total_k, cabang.sub_wilayah_id')
                            ->join('cabang', 'cabang.id', '=', 'summary.cabang_id')
                            ->where('summary.status', 1);
            }
            // ----------------------------------------------------------------
        }else if($filterYear != null){
            $year = $filterYear;
            // ----------------------------------------------------------------
            // If cabang not null
            // ----------------------------------------------------------------
            if($cabang != null) $query = Summary::selectRaw('tahun, SUM(uang_pendaftaran) as total_up, SUM(uang_kursus) as total_k, cabang_id')->where('status', 1);
            // ----------------------------------------------------------------
            // If wilayah not null
            // ----------------------------------------------------------------
            if($wilayah != null){
                $query = DB::table('summary')
                            ->selectRaw('summary.tahun, SUM(summary.uang_pendaftaran) as total_up, SUM(summary.uang_kursus) as total_k, cabang.wilayah_id')
                            ->join('cabang', 'cabang.id', '=', 'summary.cabang_id')
                            ->where('summary.status', 1);
            }
            // ----------------------------------------------------------------
            // If cabang not null
            // ----------------------------------------------------------------
            if($subWilayah != null){
                $query = DB::table('summary')
                            ->selectRaw('summary.tahun, SUM(summary.uang_pendaftaran) as total_up, SUM(summary.uang_kursus) as total_k, cabang.sub_wilayah_id')
                            ->join('cabang', 'cabang.id', '=', 'summary.cabang_id')
                            ->where('summary.status', 1);
            }
            // ----------------------------------------------------------------
        }
        // --------------------------------------------------------------------
        // Where Month
        // --------------------------------------------------------------------
        if($filterDate != null){
            $query->where(function($query)use($month){
                foreach($month as $row){
                    $month_format = strlen($row) == 1 ? "0".$row : $row;
                    $query->orWhere('bulan', $month_format);
                }
            });
        }
        // --------------------------------------------------------------------
        // Where Year
        // --------------------------------------------------------------------
        $query->whereBetween('tahun', $year);
        // --------------------------------------------------------------------
        // Where Cabang
        // --------------------------------------------------------------------
        if($cabang != null){
            $query->where(function($query)use($cabang){
                $query->whereIn('cabang_id', $cabang);
            });
        }else if(Auth::user()->level_id == 2){
            $query->where(function($query){
                $query->where('cabang.user_id', Auth::user()->id);
            });
        }
        // --------------------------------------------------------------------
        // Where wilayah
        // --------------------------------------------------------------------
        if($wilayah != null){
            $query->whereIn('cabang.wilayah_id', $wilayah);
            if(Auth::user()->level_id == 2){
                $query->where('cabang.user_id', Auth::user()->id);
            }
        }
        // --------------------------------------------------------------------
        // Where sub wilayah
        // --------------------------------------------------------------------
        if($subWilayah != null){
            $query->whereIn('cabang.sub_wilayah_id', $subWilayah);
            if(Auth::user()->level_id == 2){
                $query->where('cabang.user_id', Auth::user()->id);
            }
        }
        // --------------------------------------------------------------------
        // Order by data
        // --------------------------------------------------------------------
        $query->orderBy('tahun', 'asc');
        if($filterDate != null){
            $query->orderBy('bulan', 'asc');
        }
        // --------------------------------------------------------------------
        // Group by data
        // --------------------------------------------------------------------
        if($filterDate != null){
            if($cabang != null) $query->groupBy('tahun', 'bulan', 'cabang_id');
            if($wilayah != null) $query->groupBy('tahun', 'bulan', 'wilayah_id');
            if($subWilayah != null) $query->groupBy('tahun', 'bulan', 'sub_wilayah_id');
        }else if($filterYear != null){
            if($cabang != null) $query->groupBy('tahun', 'cabang_id');
            if($wilayah != null) $query->groupBy('tahun', 'wilayah_id');
            if($subWilayah != null) $query->groupBy('tahun', 'sub_wilayah_id');
        }
        // -------------------------------------------------------------------
        $labels = $this->getLabel($filterDate, $filterYear, $cabang, $wilayah, $subWilayah);
        $totalPendaftaran = [];
        $totalKursus = [];
        $totalPenerimaan = [];
        if($filterDate != null){
            for($i = 0; $i < count($labels); $i++){
                $labelsArray = explode(' / ', $labels[$i]);
                // -----------------------------------------------------------
                $bulan = Carbon::parse('01 '.$labelsArray[0])->format('m');
                $tahun = Carbon::parse('01 '.$labelsArray[0])->format('Y');
                // -----------------------------------------------------------
                // Pendaftaran
                $status = true;
                foreach($query->get() as $row){
                    // -------------------------------------------------------
                    // Search cabang
                    // -------------------------------------------------------
                    if($cabang != null) $location = $cabangColl->where('id', $row->cabang_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    // Search wilayah
                    // -------------------------------------------------------
                    if($wilayah != null) $location = $wilayahColl->where('id', $row->wilayah_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    // Search sub wilayah
                    // -------------------------------------------------------
                    if($subWilayah != null) $location = $subWilayahColl->where('id', $row->sub_wilayah_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    if($row->bulan == $bulan && $row->tahun == $tahun && $location != null){
                        $totalPendaftaran[] = (int)$row->total_up;
                        $status = false;
                    }
                }

                if($status) $totalPendaftaran[] = 0;
                // -----------------------------------------------------------

                // Kursus
                $status = true;
                foreach($query->get() as $row){
                    // -------------------------------------------------------
                    // Search cabang
                    // -------------------------------------------------------
                    if($cabang != null) $location = $cabangColl->where('id', $row->cabang_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    // Search wilayah
                    // -------------------------------------------------------
                    if($wilayah != null) $location = $wilayahColl->where('id', $row->wilayah_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    // Search sub wilayah
                    // -------------------------------------------------------
                    if($subWilayah != null) $location = $subWilayahColl->where('id', $row->sub_wilayah_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    if($row->bulan == $bulan && $row->tahun == $tahun && $location != null){
                        $totalKursus[] = (int)$row->total_k;
                        $status = false;
                    }
                }

                if($status) $totalKursus[] = 0;
                // -----------------------------------------------------------

                // penerimaan
                $status = true;
                foreach($query->get() as $row){
                    // -------------------------------------------------------
                    // Search cabang
                    // -------------------------------------------------------
                    if($cabang != null) $location = $cabangColl->where('id', $row->cabang_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    // Search wilayah
                    // -------------------------------------------------------
                    if($wilayah != null) $location = $wilayahColl->where('id', $row->wilayah_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    // Search sub wilayah
                    // -------------------------------------------------------
                    if($subWilayah != null) $location = $subWilayahColl->where('id', $row->sub_wilayah_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    if($row->bulan == $bulan && $row->tahun == $tahun && $location != null){
                        $totalPenerimaan[] = (int)$row->total_k + (int)$row->total_up;
                        $status = false;
                    }
                }

                if($status) $totalPenerimaan[] = 0;
                // -----------------------------------------------------------
            }
        }else if($filterYear != null){
            for($i = 0; $i < count($labels); $i++){
                $labelsArray = explode(' / ', $labels[$i]);
                // -----------------------------------------------------------
                $tahun = $labelsArray[0];
                // -----------------------------------------------------------
                $status = true;
                foreach($query->get() as $row){
                    // -------------------------------------------------------
                    // Search cabang
                    // -------------------------------------------------------
                    if($cabang != null) $location = $cabangColl->where('id', $row->cabang_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    // Search wilayah
                    // -------------------------------------------------------
                    if($wilayah != null) $location = $wilayahColl->where('id', $row->wilayah_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    // Search sub wilayah
                    // -------------------------------------------------------
                    if($subWilayah != null) $location = $subWilayahColl->where('id', $row->sub_wilayah_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    if($row->tahun == $tahun && $location != null){
                        $totalPendaftaran[] = (int)$row->total_up;
                        $status = false;
                    }
                }

                if($status) $totalPendaftaran[] = 0;
                // -----------------------------------------------------------

                // Kursus
                $status = true;
                foreach($query->get() as $row){
                    // -------------------------------------------------------
                    // Search cabang
                    // -------------------------------------------------------
                    if($cabang != null) $location = $cabangColl->where('id', $row->cabang_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    // Search wilayah
                    // -------------------------------------------------------
                    if($wilayah != null) $location = $wilayahColl->where('id', $row->wilayah_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    // Search sub wilayah
                    // -------------------------------------------------------
                    if($subWilayah != null) $location = $subWilayahColl->where('id', $row->sub_wilayah_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    if($row->tahun == $tahun && $location != null){
                        $totalKursus[] = (int)$row->total_k;
                        $status = false;
                    }
                }

                if($status) $totalKursus[] = 0;
                // -----------------------------------------------------------

                // penerimaan
                $status = true;
                foreach($query->get() as $row){
                    // -------------------------------------------------------
                    // Search cabang
                    // -------------------------------------------------------
                    if($cabang != null) $location = $cabangColl->where('id', $row->cabang_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    // Search wilayah
                    // -------------------------------------------------------
                    if($wilayah != null) $location = $wilayahColl->where('id', $row->wilayah_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    // Search sub wilayah
                    // -------------------------------------------------------
                    if($subWilayah != null) $location = $subWilayahColl->where('id', $row->sub_wilayah_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    if($row->tahun == $tahun && $location != null){
                        $totalPenerimaan[] = (int)$row->total_k + (int)$row->total_up;
                        $status = false;
                    }
                }

                if($status) $totalPenerimaan[] = 0;
                // -----------------------------------------------------------
            }
        }

        // Result
        $result = [
            [
                'label' => 'Total Penerimaan',
                'backgroundColor' => '#f39c12',
                'data' => $totalPenerimaan,
                'type' => 'line',
                'fill' => false,
                'borderColor' => '#f39c12',
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
        return $result;
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    // Get data royalti summery (Chart data)
    // ------------------------------------------------------------------------
    public function getDataSetRoyalti($filterDate, $cabang = null, $wilayah = null, $subWilayah = null, $filterYear = null){
        // --------------------------------------------------------------------
        // init data collection for set data on array
        // --------------------------------------------------------------------
        $cabangColl = Cabang::all();
        $wilayahColl = Wilayah::all();
        $subWilayahColl = SubWilayah::all();
        // --------------------------------------------------------------------
        // Make condition if filter with date or year
        // --------------------------------------------------------------------
        if($filterDate != null){
            // ----------------------------------------------------------------
            $month = $this->setMonth($filterDate);
            // ----------------------------------------------------------------
            $year[0] = Carbon::parse('01 '.$filterDate[0])->format('Y');
            $year[1] = Carbon::parse('01 '.$filterDate[1])->format('Y');
            // ----------------------------------------------------------------
            // If cabang not null
            // ----------------------------------------------------------------
            if($cabang != null) $query = Summary::selectRaw('bulan, tahun, SUM(uang_pendaftaran) as total_up, SUM(uang_kursus) as total_k, cabang_id')->where('status', 1);
            // ----------------------------------------------------------------
            // If wilayah not null
            // ----------------------------------------------------------------
            if($wilayah != null){
                $query = DB::table('summary')
                            ->selectRaw('summary.bulan, summary.tahun, SUM(summary.uang_pendaftaran) as total_up, SUM(summary.uang_kursus) as total_k, cabang.wilayah_id')
                            ->join('cabang', 'cabang.id', '=', 'summary.cabang_id')
                            ->where('summary.status', 1);
            }
            // ----------------------------------------------------------------
            // If cabang not null
            // ----------------------------------------------------------------
            if($subWilayah != null){
                $query = DB::table('summary')
                            ->selectRaw('summary.bulan, summary.tahun, SUM(summary.uang_pendaftaran) as total_up, SUM(summary.uang_kursus) as total_k, cabang.sub_wilayah_id')
                            ->join('cabang', 'cabang.id', '=', 'summary.cabang_id')
                            ->where('summary.status', 1);
            }
            // ----------------------------------------------------------------
        }else if($filterYear != null){
            $year = $filterYear;
            // ----------------------------------------------------------------
            // If cabang not null
            // ----------------------------------------------------------------
            if($cabang != null) $query = Summary::selectRaw('tahun, SUM(uang_pendaftaran) as total_up, SUM(uang_kursus) as total_k, cabang_id')->where('status', 1);
            // ----------------------------------------------------------------
            // If wilayah not null
            // ----------------------------------------------------------------
            if($wilayah != null){
                $query = DB::table('summary')
                            ->selectRaw('summary.tahun, SUM(summary.uang_pendaftaran) as total_up, SUM(summary.uang_kursus) as total_k, cabang.wilayah_id')
                            ->join('cabang', 'cabang.id', '=', 'summary.cabang_id')
                            ->where('summary.status', 1);
            }
            // ----------------------------------------------------------------
            // If cabang not null
            // ----------------------------------------------------------------
            if($subWilayah != null){
                $query = DB::table('summary')
                            ->selectRaw('summary.tahun, SUM(summary.uang_pendaftaran) as total_up, SUM(summary.uang_kursus) as total_k, cabang.sub_wilayah_id')
                            ->join('cabang', 'cabang.id', '=', 'summary.cabang_id')
                            ->where('summary.status', 1);
            }
            // ----------------------------------------------------------------
        }
        // --------------------------------------------------------------------
        // Where Month
        // --------------------------------------------------------------------
        if($filterDate != null){
            $query->where(function($query)use($month){
                foreach($month as $row){
                    $month_format = strlen($row) == 1 ? "0".$row : $row;
                    $query->orWhere('bulan', $month_format);
                }
            });
        }
        // --------------------------------------------------------------------
        // Where Year
        // --------------------------------------------------------------------
        $query->whereBetween('tahun', $year);
        // --------------------------------------------------------------------
        // Where Cabang
        // --------------------------------------------------------------------
        if($cabang != null){
            $query->where(function($query)use($cabang){
                $query->whereIn('cabang_id', $cabang);
            });
        }else if(Auth::user()->level_id == 2){
            $query->where(function($query){
                $query->where('cabang.user_id', Auth::user()->id);
            });
        }
        // --------------------------------------------------------------------
        // Where wilayah
        // --------------------------------------------------------------------
        if($wilayah != null){
            $query->whereIn('cabang.wilayah_id', $wilayah);
            if(Auth::user()->level_id == 2){
                $query->where('cabang.user_id', Auth::user()->id);
            }
        }
        // --------------------------------------------------------------------
        // Where sub wilayah
        // --------------------------------------------------------------------
        if($subWilayah != null){
            $query->whereIn('cabang.sub_wilayah_id', $subWilayah);
            if(Auth::user()->level_id == 2){
                $query->where('cabang.user_id', Auth::user()->id);
            }
        }
        // --------------------------------------------------------------------
        // $query->orderBy('tahun', 'desc')->orderBy('bulan', 'asc');
        // --------------------------------------------------------------------
        $query->orderBy('tahun', 'asc');
        if($filterDate != null){
            $query->orderBy('bulan', 'asc');
        }
        // --------------------------------------------------------------------
        // Set array dataset
        // --------------------------------------------------------------------
        if($filterDate != null){
            if($cabang != null) $query->groupBy('tahun', 'bulan', 'cabang_id');
            if($wilayah != null) $query->groupBy('tahun', 'bulan', 'wilayah_id');
            if($subWilayah != null) $query->groupBy('tahun', 'bulan', 'sub_wilayah_id');
        }else if($filterYear != null){
            if($cabang != null) $query->groupBy('tahun', 'cabang_id');
            if($wilayah != null) $query->groupBy('tahun', 'wilayah_id');
            if($subWilayah != null) $query->groupBy('tahun', 'sub_wilayah_id');
        }
        // --------------------------------------------------------------------
        $labels = $this->getLabel($filterDate, $filterYear, $cabang, $wilayah, $subWilayah);
        $totalRoyalti = [];
        // Pendaftaran
        if($filterDate != null){
            for($i = 0; $i < count($labels); $i++){
                $labelsArray = explode(' / ', $labels[$i]);
                // -----------------------------------------------------------
                $bulan = Carbon::parse('01 '.$labelsArray[0])->format('m');
                $tahun = Carbon::parse('01 '.$labelsArray[0])->format('Y');
                // -----------------------------------------------------------
                // royalti
                $status = true;
                foreach($query->get() as $row){
                    // -------------------------------------------------------
                    // Search cabang
                    // -------------------------------------------------------
                    if($cabang != null) $location = $cabangColl->where('id', $row->cabang_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    // Search wilayah
                    // -------------------------------------------------------
                    if($wilayah != null) $location = $wilayahColl->where('id', $row->wilayah_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    // Search sub wilayah
                    // -------------------------------------------------------
                    if($subWilayah != null) $location = $subWilayahColl->where('id', $row->sub_wilayah_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    if($row->bulan == $bulan && $row->tahun == $tahun && $location != null){
                        $totalRoyalti[] = ((int)$row->total_k + (int)$row->total_up) * 0.1;
                        $status = false;
                    }
                }

                if($status) $totalRoyalti[] = 0;
                // -----------------------------------------------------------
            }
        }else if($filterYear != null){
            for($i = 0; $i < count($labels); $i++){
                $labelsArray = explode(' / ', $labels[$i]);
                // -----------------------------------------------------------
                $tahun = $labelsArray[0];
                // -----------------------------------------------------------
                // royalti
                $status = true;
                foreach($query->get() as $row){
                    // -------------------------------------------------------
                    // Search cabang
                    // -------------------------------------------------------
                    if($cabang != null) $location = $cabangColl->where('id', $row->cabang_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    // Search wilayah
                    // -------------------------------------------------------
                    if($wilayah != null) $location = $wilayahColl->where('id', $row->wilayah_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    // Search sub wilayah
                    // -------------------------------------------------------
                    if($subWilayah != null) $location = $subWilayahColl->where('id', $row->sub_wilayah_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    if($row->tahun == $tahun && $location != null && $location != null){
                        $totalRoyalti[] = ((int)$row->total_k + (int)$row->total_up) * 0.1;
                        $status = false;
                    }
                }

                if($status) $totalRoyalti[] = 0;
                // -----------------------------------------------------------
            }
        }

        // Result
        $result = [
            [
                'label'             => 'Royalti',
                'backgroundColor'   => '#74b9ff',
                'data'              => $totalRoyalti,
            ],
        ];
        // --------------------------------------------------------------------
        return $result;
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    // Get data siswa aktif summery (Chart data)
    // ------------------------------------------------------------------------
    public function getDataSetSiswaAktif($filterDate, $cabang = null, $wilayah = null, $subWilayah = null, $filterYear = null){
        // --------------------------------------------------------------------
        // init data collection for set data on array
        // --------------------------------------------------------------------
        $cabangColl = Cabang::all();
        $wilayahColl = Wilayah::all();
        $subWilayahColl = SubWilayah::all();
        // --------------------------------------------------------------------
        // Make condition if filter with date or year
        // --------------------------------------------------------------------
        if($filterDate != null){
            $month = $this->setMonth($filterDate);
            // ----------------------------------------------------------------
            $year[0] = Carbon::parse('01 '.$filterDate[0])->format('Y');
            $year[1] = Carbon::parse('01 '.$filterDate[1])->format('Y');
            // ----------------------------------------------------------------
            // If cabang not null
            // ----------------------------------------------------------------
            if($cabang != null) $query = Summary::selectRaw('bulan, tahun, SUM(siswa_aktif) as total_sa, SUM(siswa_baru) as total_sb, SUM(siswa_cuti) as total_sc, SUM(siswa_keluar) as total_sk, cabang_id')->where('status', 1);
            // ----------------------------------------------------------------
            // If wilayah not null
            // ----------------------------------------------------------------
            if($wilayah != null){
                $query = DB::table('summary')
                            ->selectRaw('summary.bulan, summary.tahun, SUM(summary.siswa_aktif) as total_sa, SUM(summary.siswa_baru) as total_sb, SUM(summary.siswa_cuti) as total_sc, SUM(summary.siswa_keluar) as total_sk, cabang.wilayah_id')
                            ->join('cabang', 'cabang.id', '=', 'summary.cabang_id')
                            ->where('summary.status', 1);
            }
            // ----------------------------------------------------------------
            // If cabang not null
            // ----------------------------------------------------------------
            if($subWilayah != null){
                $query = DB::table('summary')
                            ->selectRaw('summary.bulan, summary.tahun, SUM(summary.siswa_aktif) as total_sa, SUM(summary.siswa_baru) as total_sb, SUM(summary.siswa_cuti) as total_sc, SUM(summary.siswa_keluar) as total_sk, cabang.sub_wilayah_id')
                            ->join('cabang', 'cabang.id', '=', 'summary.cabang_id')
                            ->where('summary.status', 1);
            }
            // ----------------------------------------------------------------
        }else if($filterYear != null){
            $year = $filterYear;
            // ----------------------------------------------------------------
            // If cabang not null
            // ----------------------------------------------------------------
            if($cabang != null) $query = Summary::selectRaw('tahun, SUM(siswa_aktif) as total_sa, SUM(siswa_baru) as total_sb, SUM(siswa_cuti) as total_sc, SUM(siswa_keluar) as total_sk, cabang_id')->where('status', 1);
            // ----------------------------------------------------------------
            // If wilayah not null
            // ----------------------------------------------------------------
            if($wilayah != null){
                $query = DB::table('summary')
                            ->selectRaw('summary.tahun, SUM(summary.siswa_aktif) as total_sa, SUM(summary.siswa_baru) as total_sb, SUM(summary.siswa_cuti) as total_sc, SUM(summary.siswa_keluar) as total_sk, cabang.wilayah_id')
                            ->join('cabang', 'cabang.id', '=', 'summary.cabang_id')
                            ->where('summary.status', 1);
            }
            // ----------------------------------------------------------------
            // If cabang not null
            // ----------------------------------------------------------------
            if($subWilayah != null){
                $query = DB::table('summary')
                            ->selectRaw('summary.tahun, SUM(summary.siswa_aktif) as total_sa, SUM(summary.siswa_baru) as total_sb, SUM(summary.siswa_cuti) as total_sc, SUM(summary.siswa_keluar) as total_sk, cabang.sub_wilayah_id')
                            ->join('cabang', 'cabang.id', '=', 'summary.cabang_id')
                            ->where('summary.status', 1);
            }
            // ----------------------------------------------------------------
        }
        // --------------------------------------------------------------------
        // Where Month
        // --------------------------------------------------------------------
        if($filterDate != null){
            $query->where(function($query)use($month){
                foreach($month as $row){
                    $month_format = strlen($row) == 1 ? "0".$row : $row;
                    $query->orWhere('bulan', $month_format);
                }
            });
        }
        // --------------------------------------------------------------------
        // Where Year
        // --------------------------------------------------------------------
        $query->whereBetween('tahun', $year);
        // --------------------------------------------------------------------
        // Where Cabang
        // --------------------------------------------------------------------
        if($cabang != null){
            $query->where(function($query)use($cabang){
                $query->whereIn('cabang_id', $cabang);
            });
        }else if(Auth::user()->level_id == 2){
            $query->where(function($query){
                $query->where('cabang.user_id', Auth::user()->id);
            });
        }
        // --------------------------------------------------------------------
        // Where wilayah
        // --------------------------------------------------------------------
        if($wilayah != null){
            $query->whereIn('cabang.wilayah_id', $wilayah);
            if(Auth::user()->level_id == 2){
                $query->where('cabang.user_id', Auth::user()->id);
            }
        }
        // --------------------------------------------------------------------
        // Where sub wilayah
        // --------------------------------------------------------------------
        if($subWilayah != null){
            $query->whereIn('cabang.sub_wilayah_id', $subWilayah);
            if(Auth::user()->level_id == 2){
                $query->where('cabang.user_id', Auth::user()->id);
            }
        }
        // --------------------------------------------------------------------
        // $query->orderBy('tahun', 'desc')->orderBy('bulan', 'asc');
        // --------------------------------------------------------------------
        $query->orderBy('tahun', 'asc');
        if($filterDate != null){
            $query->orderBy('bulan', 'asc');
        }
        // --------------------------------------------------------------------
        // Set array dataset
        // --------------------------------------------------------------------
        if($filterDate != null){
            if($cabang != null) $query->groupBy('tahun', 'bulan', 'cabang_id');
            if($wilayah != null) $query->groupBy('tahun', 'bulan', 'wilayah_id');
            if($subWilayah != null) $query->groupBy('tahun', 'bulan', 'sub_wilayah_id');
        }else if($filterYear != null){
            if($cabang != null) $query->groupBy('tahun', 'cabang_id');
            if($wilayah != null) $query->groupBy('tahun', 'wilayah_id');
            if($subWilayah != null) $query->groupBy('tahun', 'sub_wilayah_id');
        }
        // --------------------------------------------------------------------
        $labels = $this->getLabel($filterDate, $filterYear, $cabang, $wilayah, $subWilayah);
        $totalSiswaAktif = [];
        $totalSiswaBaru = [];
        $totalSiswaCuti = [];
        $totalSiswaKeluar = [];
        // Pendaftaran
        if($filterDate != null){
            for($i = 0; $i < count($labels); $i++){
                $labelsArray = explode(' / ', $labels[$i]);
                // -----------------------------------------------------------
                $bulan = Carbon::parse('01 '.$labelsArray[0])->format('m');
                $tahun = Carbon::parse('01 '.$labelsArray[0])->format('Y');
                // -----------------------------------------------------------
                // Siswa aktif
                $status = true;
                foreach($query->get() as $row){
                    // -------------------------------------------------------
                    // Search cabang
                    // -------------------------------------------------------
                    if($cabang != null) $location = $cabangColl->where('id', $row->cabang_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    // Search wilayah
                    // -------------------------------------------------------
                    if($wilayah != null) $location = $wilayahColl->where('id', $row->wilayah_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    // Search sub wilayah
                    // -------------------------------------------------------
                    if($subWilayah != null) $location = $subWilayahColl->where('id', $row->sub_wilayah_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    if($row->bulan == $bulan && $row->tahun == $tahun && $location != null){
                        $totalSiswaAktif[] = (int)$row->total_sa;
                        $status = false;
                    }
                }

                if($status) $totalSiswaAktif[] = 0;
                // -----------------------------------------------------------
                // Siswa baru
                $status = true;
                foreach($query->get() as $row){
                    // -------------------------------------------------------
                    // Search cabang
                    // -------------------------------------------------------
                    if($cabang != null) $location = $cabangColl->where('id', $row->cabang_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    // Search wilayah
                    // -------------------------------------------------------
                    if($wilayah != null) $location = $wilayahColl->where('id', $row->wilayah_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    // Search sub wilayah
                    // -------------------------------------------------------
                    if($subWilayah != null) $location = $subWilayahColl->where('id', $row->sub_wilayah_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    if($row->bulan == $bulan && $row->tahun == $tahun && $location != null){
                        $totalSiswaBaru[] = (int)$row->total_sb;
                        $status = false;
                    }
                }

                if($status) $totalSiswaBaru[] = 0;
                // -----------------------------------------------------------
                // Siswa cuti
                $status = true;
                foreach($query->get() as $row){
                    // -------------------------------------------------------
                    // Search cabang
                    // -------------------------------------------------------
                    if($cabang != null) $location = $cabangColl->where('id', $row->cabang_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    // Search wilayah
                    // -------------------------------------------------------
                    if($wilayah != null) $location = $wilayahColl->where('id', $row->wilayah_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    // Search sub wilayah
                    // -------------------------------------------------------
                    if($subWilayah != null) $location = $subWilayahColl->where('id', $row->sub_wilayah_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    if($row->bulan == $bulan && $row->tahun == $tahun && $location != null){
                        $totalSiswaCuti[] = (int)$row->total_sc;
                        $status = false;
                    }
                }

                if($status) $totalSiswaCuti[] = 0;
                // -----------------------------------------------------------
                // Siswa keluar
                $status = true;
                foreach($query->get() as $row){
                    // -------------------------------------------------------
                    // Search cabang
                    // -------------------------------------------------------
                    if($cabang != null) $location = $cabangColl->where('id', $row->cabang_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    // Search wilayah
                    // -------------------------------------------------------
                    if($wilayah != null) $location = $wilayahColl->where('id', $row->wilayah_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    // Search sub wilayah
                    // -------------------------------------------------------
                    if($subWilayah != null) $location = $subWilayahColl->where('id', $row->sub_wilayah_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    if($row->bulan == $bulan && $row->tahun == $tahun && $location != null){
                        $totalSiswaKeluar[] = (int)$row->total_sk;
                        $status = false;
                    }
                }

                if($status) $totalSiswaKeluar[] = 0;
                // -----------------------------------------------------------
            }
        }else if($filterYear != null){
            for($i = 0; $i < count($labels); $i++){
                $labelsArray = explode(' / ', $labels[$i]);
                // -----------------------------------------------------------
                $tahun = $labelsArray[0];
                // -----------------------------------------------------------
                // Siswa aktif
                $status = true;
                foreach($query->get() as $row){
                    // -------------------------------------------------------
                    // Search cabang
                    // -------------------------------------------------------
                    if($cabang != null) $location = $cabangColl->where('id', $row->cabang_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    // Search wilayah
                    // -------------------------------------------------------
                    if($wilayah != null) $location = $wilayahColl->where('id', $row->wilayah_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    // Search sub wilayah
                    // -------------------------------------------------------
                    if($subWilayah != null) $location = $subWilayahColl->where('id', $row->sub_wilayah_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    if($row->tahun == $tahun && $location != null){
                        $totalSiswaAktif[] = (int)$row->total_sa;
                        $status = false;
                    }
                }

                if($status) $totalSiswaAktif[] = 0;
                // -----------------------------------------------------------
                // Siswa baru
                $status = true;
                foreach($query->get() as $row){
                    // -------------------------------------------------------
                    // Search cabang
                    // -------------------------------------------------------
                    if($cabang != null) $location = $cabangColl->where('id', $row->cabang_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    // Search wilayah
                    // -------------------------------------------------------
                    if($wilayah != null) $location = $wilayahColl->where('id', $row->wilayah_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    // Search sub wilayah
                    // -------------------------------------------------------
                    if($subWilayah != null) $location = $subWilayahColl->where('id', $row->sub_wilayah_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    if($row->tahun == $tahun && $location != null){
                        $totalSiswaBaru[] = (int)$row->total_sb;
                        $status = false;
                    }
                }

                if($status) $totalSiswaBaru[] = 0;
                // -----------------------------------------------------------
                // Siswa cuti
                $status = true;
                foreach($query->get() as $row){
                    // -------------------------------------------------------
                    // Search cabang
                    // -------------------------------------------------------
                    if($cabang != null) $location = $cabangColl->where('id', $row->cabang_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    // Search wilayah
                    // -------------------------------------------------------
                    if($wilayah != null) $location = $wilayahColl->where('id', $row->wilayah_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    // Search sub wilayah
                    // -------------------------------------------------------
                    if($subWilayah != null) $location = $subWilayahColl->where('id', $row->sub_wilayah_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    if($row->tahun == $tahun && $location != null){
                        $totalSiswaCuti[] = (int)$row->total_sc;
                        $status = false;
                    }
                }

                if($status) $totalSiswaCuti[] = 0;
                // -----------------------------------------------------------
                // Siswa keluar
                $status = true;
                foreach($query->get() as $row){
                    // -------------------------------------------------------
                    // Search cabang
                    // -------------------------------------------------------
                    if($cabang != null) $location = $cabangColl->where('id', $row->cabang_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    // Search wilayah
                    // -------------------------------------------------------
                    if($wilayah != null) $location = $wilayahColl->where('id', $row->wilayah_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    // Search sub wilayah
                    // -------------------------------------------------------
                    if($subWilayah != null) $location = $subWilayahColl->where('id', $row->sub_wilayah_id)->where('nama', $labelsArray[1])->first();
                    // -------------------------------------------------------
                    if($row->tahun == $tahun && $location != null){
                        $totalSiswaKeluar[] = (int)$row->total_sk;
                        $status = false;
                    }
                }

                if($status) $totalSiswaKeluar[] = 0;
                // -----------------------------------------------------------
            }
        }

        // Result
        $result = [
            [
                'label'             => 'Siswa Aktif',
                'backgroundColor'   => '#3498db',
                'data'              => $totalSiswaAktif,
                'stack'             => 'stack_1',
            ],
            [
                'label'             => 'Siswa Baru',
                'backgroundColor'   => '#d35400',
                'data'              => $totalSiswaBaru,
                'stack'             => 'stack_1',
            ],
            [
                'label'             => 'Siswa Cuti',
                'backgroundColor'   => '#27ae60',
                'data'              => $totalSiswaCuti,
                'stack'             => 'stack_1',
            ],
            [
                'label'             => 'Siswa Keluar',
                'backgroundColor'   => '#8e44ad',
                'data'              => $totalSiswaKeluar,
                'stack'             => 'stack_1',
            ],
        ];
        // --------------------------------------------------------------------
        return $result;
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    // Get data siswa aktif jurusan summery (Chart data)
    // ------------------------------------------------------------------------
    public function getDataSetSiswaAktifJurusan($filterDate, $cabang = null, $wilayah = null, $subWilayah = null, $filterYear = null){
        // --------------------------------------------------------------------
        // init data collection for set data on array
        // --------------------------------------------------------------------
        $cabangColl = Cabang::all();
        $wilayahColl = Wilayah::all();
        $subWilayahColl = SubWilayah::all();
        // --------------------------------------------------------------------
        // Make condition if filter with date or year
        // --------------------------------------------------------------------
        if($filterDate != null){
            $month = $this->setMonth($filterDate);
            // ----------------------------------------------------------------
            $year[0] = Carbon::parse('01 '.$filterDate[0])->format('Y');
            $year[1] = Carbon::parse('01 '.$filterDate[1])->format('Y');
            // ----------------------------------------------------------------
            $query = Summary::select('id', 'bulan', 'tahun')->where('status', 1);
        }else if($filterYear != null){
            $year = $filterYear;
            $query = Summary::select('id', 'tahun')->where('status', 1);
        }
        // --------------------------------------------------------------------
        // Where Month
        // --------------------------------------------------------------------
        if($filterDate != null){
            $query->where(function($query)use($month){
                foreach($month as $row){
                    $month_format = strlen($row) == 1 ? "0".$row : $row;
                    $query->orWhere('bulan', $month_format);
                }
            });
        }
        // --------------------------------------------------------------------
        // Where Year
        // --------------------------------------------------------------------
        $query->whereBetween('tahun', $year);
        // --------------------------------------------------------------------
        // Where Cabang
        // --------------------------------------------------------------------
        if($cabang != null){
            $query->where(function($query)use($cabang){
                $query->whereIn('cabang_id', $cabang);
            });
        }else if(Auth::user()->level_id == 2){
            $query->where(function($query){
                $query->where('cabang.user_id', Auth::user()->id);
            });
        }
        // --------------------------------------------------------------------
        // Where wilayah
        // --------------------------------------------------------------------
        if($wilayah != null){
            $query->where(function($query)use($wilayah){
                $query->whereHas('cabang', function($query)use($wilayah){
                    $query->whereIn('wilayah_id', $wilayah);        
                });
            });
        }
        // --------------------------------------------------------------------
        // Where sub wilayah
        // --------------------------------------------------------------------
        if($subWilayah != null){
            $query->where(function($query)use($subWilayah){
                $query->whereHas('cabang', function($query)use($subWilayah){
                    $query->whereIn('sub_wilayah_id', $subWilayah);        
                });
            });
        }
        // --------------------------------------------------------------------
        // $query->orderBy('tahun', 'desc')->orderBy('bulan', 'asc');
        // --------------------------------------------------------------------
        $query->orderBy('tahun', 'asc');
        if($filterDate != null){
            $query->orderBy('bulan', 'asc');
        }
        // --------------------------------------------------------------------
        $summaryArray = $query->pluck('id')->toArray();
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Get data summary sa jurusan
        // --------------------------------------------------------------------
        if($filterDate != null){
            // ----------------------------------------------------------------
            // Get data if filtering by cabang
            // ----------------------------------------------------------------
            if($cabang != null){
                $relationship = DB::table('summary_sa_materi')
                                ->selectRaw('SUM(summary_sa_materi.jumlah) as total_jumlah, materi.id as materi_id, summary.bulan, summary.tahun, summary.cabang_id')
                                ->join('summary', 'summary_sa_materi.summary_id', '=', 'summary.id')
                                ->join('materi', 'summary_sa_materi.materi_id', '=', 'materi.id')
                                ->whereIn('summary_sa_materi.summary_id', $summaryArray)
                                ->orderBy('summary.tahun', 'asc')
                                ->orderBy('summary.bulan', 'asc')
                                ->groupBy('summary.tahun', 'summary.bulan', 'summary.cabang_id', 'materi.id');
            }
            // ----------------------------------------------------------------
            // Get data if filtering by wilayah
            // ----------------------------------------------------------------
            if($wilayah != null){
                $relationship = DB::table('summary_sa_materi')
                                ->selectRaw('SUM(summary_sa_materi.jumlah) as total_jumlah, materi.id as materi_id, summary.bulan, summary.tahun, cabang.wilayah_id')
                                ->join('summary', 'summary_sa_materi.summary_id', '=', 'summary.id')
                                ->join('materi', 'summary_sa_materi.materi_id', '=', 'materi.id')
                                ->join('cabang', 'summary.cabang_id', '=', 'cabang.id')
                                ->whereIn('summary_sa_materi.summary_id', $summaryArray)
                                ->orderBy('summary.tahun', 'asc')
                                ->orderBy('summary.bulan', 'asc')
                                ->groupBy('summary.tahun', 'summary.bulan', 'cabang.wilayah_id', 'materi.id');
            }
            // ----------------------------------------------------------------
            // Get data if filtering by sub wilayah
            // ----------------------------------------------------------------
            if($subWilayah != null){
                $relationship = DB::table('summary_sa_materi')
                                ->selectRaw('SUM(summary_sa_materi.jumlah) as total_jumlah, materi.id as materi_id, summary.bulan, summary.tahun, cabang.sub_wilayah_id')
                                ->join('summary', 'summary_sa_materi.summary_id', '=', 'summary.id')
                                ->join('materi', 'summary_sa_materi.materi_id', '=', 'materi.id')
                                ->join('cabang', 'summary.cabang_id', '=', 'cabang.id')
                                ->whereIn('summary_sa_materi.summary_id', $summaryArray)
                                ->orderBy('summary.tahun', 'asc')
                                ->orderBy('summary.bulan', 'asc')
                                ->groupBy('summary.tahun', 'summary.bulan', 'cabang.sub_wilayah_id', 'materi.id');
            }
            // ----------------------------------------------------------------
        }else if($filterYear != null){
            // ----------------------------------------------------------------
            // Get data if filtering by cabang
            // ----------------------------------------------------------------
            if($cabang != null){
                $relationship = DB::table('summary_sa_materi')
                                ->selectRaw('SUM(summary_sa_materi.jumlah) as total_jumlah, materi.id as materi_id, summary.tahun, summary.cabang_id')
                                ->join('summary', 'summary_sa_materi.summary_id', '=', 'summary.id')
                                ->join('materi', 'summary_sa_materi.materi_id', '=', 'materi.id')
                                ->whereIn('summary_sa_materi.summary_id', $summaryArray)
                                ->orderBy('summary.tahun', 'asc')
                                ->groupBy('summary.tahun', 'summary.cabang_id', 'materi.id');
            }
            // ----------------------------------------------------------------
            // Get data if filtering by wilayah
            // ----------------------------------------------------------------
            if($wilayah != null){
                $relationship = DB::table('summary_sa_materi')
                                ->selectRaw('SUM(summary_sa_materi.jumlah) as total_jumlah, materi.id as materi_id, summary.tahun, cabang.wilayah_id')
                                ->join('summary', 'summary_sa_materi.summary_id', '=', 'summary.id')
                                ->join('materi', 'summary_sa_materi.materi_id', '=', 'materi.id')
                                ->join('cabang', 'summary.cabang_id', '=', 'cabang.id')
                                ->whereIn('summary_sa_materi.summary_id', $summaryArray)
                                ->orderBy('summary.tahun', 'asc')
                                ->groupBy('summary.tahun', 'cabang.wilayah_id', 'materi.id');
            }
            // ----------------------------------------------------------------
            // Get data if filtering by sub wilayah
            // ----------------------------------------------------------------
            if($subWilayah != null){
                $relationship = DB::table('summary_sa_materi')
                                ->selectRaw('SUM(summary_sa_materi.jumlah) as total_jumlah, materi.id as materi_id, summary.tahun, cabang.sub_wilayah_id')
                                ->join('summary', 'summary_sa_materi.summary_id', '=', 'summary.id')
                                ->join('materi', 'summary_sa_materi.materi_id', '=', 'materi.id')
                                ->join('cabang', 'summary.cabang_id', '=', 'cabang.id')
                                ->whereIn('summary_sa_materi.summary_id', $summaryArray)
                                ->orderBy('summary.tahun', 'asc')
                                ->groupBy('summary.tahun', 'cabang.sub_wilayah_id', 'materi.id');
            }
            // ----------------------------------------------------------------
        }
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Set array dataset
        // --------------------------------------------------------------------
        $data = $relationship->get();
        // --------------------------------------------------------------------
        $result = [];
        $materis = Materi::where('status', 1)->get();
        foreach($materis as $materi){
            $labels = $this->getLabel($filterDate, $filterYear, $cabang, $wilayah, $subWilayah);
            $total = [];
            if($filterDate != null){
                for($i = 0; $i < count($labels); $i++){
                    $labelsArray = explode(' / ', $labels[$i]);
                    // -----------------------------------------------------------
                    $bulan = Carbon::parse('01 '.$labelsArray[0])->format('m');
                    $tahun = Carbon::parse('01 '.$labelsArray[0])->format('Y');
                    // -----------------------------------------------------------
                    $status = true;
                    foreach($data as $row){
                        // -------------------------------------------------------
                        // Search cabang
                        // -------------------------------------------------------
                        if($cabang != null) $location = $cabangColl->where('id', $row->cabang_id)->where('nama', $labelsArray[1])->first();
                        // -------------------------------------------------------
                        // Search wilayah
                        // -------------------------------------------------------
                        if($wilayah != null) $location = $wilayahColl->where('id', $row->wilayah_id)->where('nama', $labelsArray[1])->first();
                        // -------------------------------------------------------
                        // Search sub wilayah
                        // -------------------------------------------------------
                        if($subWilayah != null) $location = $subWilayahColl->where('id', $row->sub_wilayah_id)->where('nama', $labelsArray[1])->first();
                        // -------------------------------------------------------
                        if($row->bulan == $bulan && $row->tahun == $tahun && $row->materi_id == $materi->id && $location != null){
                            $total[] = (int)$row->total_jumlah;
                            $status = false;
                        }
                    }

                    if($status) $total[] = 0;
                    // -----------------------------------------------------------
                }
            }else if($filterYear != null){
                for($i = 0; $i < count($labels); $i++){
                    $labelsArray = explode(' / ', $labels[$i]);
                    // -----------------------------------------------------------
                    $tahun = $labelsArray[0];
                    // -----------------------------------------------------------
                    // royalti
                    $status = true;
                    foreach($data as $row){
                        // -------------------------------------------------------
                        // Search cabang
                        // -------------------------------------------------------
                        if($cabang != null) $location = $cabangColl->where('id', $row->cabang_id)->where('nama', $labelsArray[1])->first();
                        // -------------------------------------------------------
                        // Search wilayah
                        // -------------------------------------------------------
                        if($wilayah != null) $location = $wilayahColl->where('id', $row->wilayah_id)->where('nama', $labelsArray[1])->first();
                        // -------------------------------------------------------
                        // Search sub wilayah
                        // -------------------------------------------------------
                        if($subWilayah != null) $location = $subWilayahColl->where('id', $row->sub_wilayah_id)->where('nama', $labelsArray[1])->first();
                        // -------------------------------------------------------
                        if($row->tahun == $tahun && $row->materi_id == $materi->id && $location != null){
                            $total[] = (int)$row->total_jumlah;
                            $status = false;
                        }
                    }

                    if($status) $total[] = 0;
                    // -----------------------------------------------------------
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
        return $result;
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    // Get data siswa aktif pendidikan summery (Chart data)
    // ------------------------------------------------------------------------
    public function getDataSetSiswaAktifpendidikan($filterDate, $cabang = null, $wilayah = null, $subWilayah = null, $filterYear = null){
        // --------------------------------------------------------------------
        // init data collection for set data on array
        // --------------------------------------------------------------------
        $cabangColl = Cabang::all();
        $wilayahColl = Wilayah::all();
        $subWilayahColl = SubWilayah::all();
        // --------------------------------------------------------------------
        // Make condition if filter with date or year
        // --------------------------------------------------------------------
        if($filterDate != null){
            $month = $this->setMonth($filterDate);
            // ----------------------------------------------------------------
            $year[0] = Carbon::parse('01 '.$filterDate[0])->format('Y');
            $year[1] = Carbon::parse('01 '.$filterDate[1])->format('Y');
            // ----------------------------------------------------------------
            $query = Summary::select('id', 'bulan', 'tahun')->where('status', 1);
        }else if($filterYear != null){
            $year = $filterYear;
            $query = Summary::select('id', 'tahun')->where('status', 1);
        }
        // --------------------------------------------------------------------
        // Where Month
        // --------------------------------------------------------------------
        if($filterDate != null){
            $query->where(function($query)use($month){
                foreach($month as $row){
                    $month_format = strlen($row) == 1 ? "0".$row : $row;
                    $query->orWhere('bulan', $month_format);
                }
            });
        }
        // --------------------------------------------------------------------
        // Where Year
        // --------------------------------------------------------------------
        $query->whereBetween('tahun', $year);
        // --------------------------------------------------------------------
        // Where Cabang
        // --------------------------------------------------------------------
        if($cabang != null){
            $query->where(function($query)use($cabang){
                $query->whereIn('cabang_id', $cabang);
            });
        }else if(Auth::user()->level_id == 2){
            $query->where(function($query){
                $query->where('cabang.user_id', Auth::user()->id);
            });
        }
        // --------------------------------------------------------------------
        // Where wilayah
        // --------------------------------------------------------------------
        if($wilayah != null){
            $query->where(function($query)use($wilayah){
                $query->whereHas('cabang', function($query)use($wilayah){
                    $query->whereIn('wilayah_id', $wilayah);        
                });
            });
        }
        // --------------------------------------------------------------------
        // Where sub wilayah
        // --------------------------------------------------------------------
        if($subWilayah != null){
            $query->where(function($query)use($subWilayah){
                $query->whereHas('cabang', function($query)use($subWilayah){
                    $query->whereIn('sub_wilayah_id', $subWilayah);        
                });
            });
        }
        // --------------------------------------------------------------------
        // $query->orderBy('tahun', 'desc')->orderBy('bulan', 'asc');
        // --------------------------------------------------------------------
        $query->orderBy('tahun', 'asc');
        if($filterDate != null){
            $query->orderBy('bulan', 'asc');
        }
        // --------------------------------------------------------------------
        $summaryArray = $query->pluck('id')->toArray();
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Get data summary sa pendidikan
        // --------------------------------------------------------------------
        if($filterDate != null){
            // ----------------------------------------------------------------
            // Get data if filtering by cabang
            // ----------------------------------------------------------------
            if($cabang != null){
                $relationship = DB::table('summary_sa_pendidikan')
                            ->selectRaw('SUM(summary_sa_pendidikan.jumlah) as total_jumlah, pendidikan.id as pendidikan_id, summary.bulan, summary.tahun, summary.cabang_id')
                            ->join('summary', 'summary_sa_pendidikan.summary_id', '=', 'summary.id')
                            ->join('pendidikan', 'summary_sa_pendidikan.pendidikan_id', '=', 'pendidikan.id')
                            ->whereIn('summary_sa_pendidikan.summary_id', $summaryArray)
                            ->orderBy('summary.tahun', 'asc')
                            ->orderBy('summary.bulan', 'asc')
                            ->groupBy('summary.tahun', 'summary.bulan', 'summary.cabang_id', 'pendidikan.id');
            }
            // ----------------------------------------------------------------
            // Get data if filtering by wilayah
            // ----------------------------------------------------------------
            if($wilayah != null){
                $relationship = DB::table('summary_sa_pendidikan')
                            ->selectRaw('SUM(summary_sa_pendidikan.jumlah) as total_jumlah, pendidikan.id as pendidikan_id, summary.bulan, summary.tahun, cabang.wilayah_id')
                            ->join('summary', 'summary_sa_pendidikan.summary_id', '=', 'summary.id')
                            ->join('pendidikan', 'summary_sa_pendidikan.pendidikan_id', '=', 'pendidikan.id')
                            ->join('cabang', 'summary.cabang_id', '=', 'cabang.id')
                            ->whereIn('summary_sa_pendidikan.summary_id', $summaryArray)
                            ->orderBy('summary.tahun', 'asc')
                            ->orderBy('summary.bulan', 'asc')
                            ->groupBy('summary.tahun', 'summary.bulan', 'cabang.wilayah_id', 'pendidikan.id');
            }
            // ----------------------------------------------------------------
            // Get data if filtering by sub wilayah
            // ----------------------------------------------------------------
            if($subWilayah != null){
                $relationship = DB::table('summary_sa_pendidikan')
                            ->selectRaw('SUM(summary_sa_pendidikan.jumlah) as total_jumlah, pendidikan.id as pendidikan_id, summary.bulan, summary.tahun, cabang.sub_wilayah_id')
                            ->join('summary', 'summary_sa_pendidikan.summary_id', '=', 'summary.id')
                            ->join('pendidikan', 'summary_sa_pendidikan.pendidikan_id', '=', 'pendidikan.id')
                            ->join('cabang', 'summary.cabang_id', '=', 'cabang.id')
                            ->whereIn('summary_sa_pendidikan.summary_id', $summaryArray)
                            ->orderBy('summary.tahun', 'asc')
                            ->orderBy('summary.bulan', 'asc')
                            ->groupBy('summary.tahun', 'summary.bulan', 'cabang.sub_wilayah_id', 'pendidikan.id');
            }
            // ----------------------------------------------------------------
        }else if($filterYear != null){
            // ----------------------------------------------------------------
            // Get data if filtering by cabang
            // ----------------------------------------------------------------
            if($cabang != null){
                $relationship = DB::table('summary_sa_pendidikan')
                            ->selectRaw('SUM(summary_sa_pendidikan.jumlah) as total_jumlah, pendidikan.id as pendidikan_id, summary.tahun, summary.cabang_id')
                            ->join('summary', 'summary_sa_pendidikan.summary_id', '=', 'summary.id')
                            ->join('pendidikan', 'summary_sa_pendidikan.pendidikan_id', '=', 'pendidikan.id')
                            ->whereIn('summary_sa_pendidikan.summary_id', $summaryArray)
                            ->orderBy('summary.tahun', 'asc')
                            ->orderBy('summary.bulan', 'asc')
                            ->groupBy('summary.tahun', 'summary.cabang_id', 'pendidikan.id');
            }
            // ----------------------------------------------------------------
            // Get data if filtering by wilayah
            // ----------------------------------------------------------------
            if($wilayah != null){
                $relationship = DB::table('summary_sa_pendidikan')
                            ->selectRaw('SUM(summary_sa_pendidikan.jumlah) as total_jumlah, pendidikan.id as pendidikan_id, summary.tahun, cabang.wilayah_id')
                            ->join('summary', 'summary_sa_pendidikan.summary_id', '=', 'summary.id')
                            ->join('pendidikan', 'summary_sa_pendidikan.pendidikan_id', '=', 'pendidikan.id')
                            ->join('cabang', 'summary.cabang_id', '=', 'cabang.id')
                            ->whereIn('summary_sa_pendidikan.summary_id', $summaryArray)
                            ->orderBy('summary.tahun', 'asc')
                            ->orderBy('summary.bulan', 'asc')
                            ->groupBy('summary.tahun', 'cabang.wilayah_id', 'pendidikan.id');
            }
            // ----------------------------------------------------------------
            // Get data if filtering by sub wilayah
            // ----------------------------------------------------------------
            if($subWilayah != null){
                $relationship = DB::table('summary_sa_pendidikan')
                            ->selectRaw('SUM(summary_sa_pendidikan.jumlah) as total_jumlah, pendidikan.id as pendidikan_id, summary.tahun, cabang.sub_wilayah_id')
                            ->join('summary', 'summary_sa_pendidikan.summary_id', '=', 'summary.id')
                            ->join('pendidikan', 'summary_sa_pendidikan.pendidikan_id', '=', 'pendidikan.id')
                            ->join('cabang', 'summary.cabang_id', '=', 'cabang.id')
                            ->whereIn('summary_sa_pendidikan.summary_id', $summaryArray)
                            ->orderBy('summary.tahun', 'asc')
                            ->orderBy('summary.bulan', 'asc')
                            ->groupBy('summary.tahun', 'cabang.sub_wilayah_id', 'pendidikan.id');
            }
            // ----------------------------------------------------------------
        }
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Set array dataset
        // --------------------------------------------------------------------
        $data = $relationship->get();
        // --------------------------------------------------------------------
        $result = [];
        $pendidikans = Pendidikan::all();
        foreach($pendidikans as $pendidikan){
            $labels = $this->getLabel($filterDate, $filterYear, $cabang, $wilayah, $subWilayah);
            $total = [];
            if($filterDate != null){
                for($i = 0; $i < count($labels); $i++){
                    $labelsArray = explode(' / ', $labels[$i]);
                    // -----------------------------------------------------------
                    $bulan = Carbon::parse('01 '.$labelsArray[0])->format('m');
                    $tahun = Carbon::parse('01 '.$labelsArray[0])->format('Y');
                    // -----------------------------------------------------------
                    $status = true;
                    foreach($data as $row){
                        // -------------------------------------------------------
                        // Search cabang
                        // -------------------------------------------------------
                        if($cabang != null) $location = $cabangColl->where('id', $row->cabang_id)->where('nama', $labelsArray[1])->first();
                        // -------------------------------------------------------
                        // Search wilayah
                        // -------------------------------------------------------
                        if($wilayah != null) $location = $wilayahColl->where('id', $row->wilayah_id)->where('nama', $labelsArray[1])->first();
                        // -------------------------------------------------------
                        // Search sub wilayah
                        // -------------------------------------------------------
                        if($subWilayah != null) $location = $subWilayahColl->where('id', $row->sub_wilayah_id)->where('nama', $labelsArray[1])->first();
                        // -------------------------------------------------------
                        if($row->bulan == $bulan && $row->tahun == $tahun && $row->pendidikan_id == $pendidikan->id && $location != null){
                            $total[] = (int)$row->total_jumlah;
                            $status = false;
                        }
                    }

                    if($status) $total[] = 0;
                    // -----------------------------------------------------------
                }
            }else if($filterYear != null){
                for($i = 0; $i < count($labels); $i++){
                    $labelsArray = explode(' / ', $labels[$i]);
                    // -----------------------------------------------------------
                    $tahun = $labelsArray[0];
                    // -----------------------------------------------------------
                    // royalti
                    $status = true;
                    foreach($data as $row){
                        // -------------------------------------------------------
                        // Search cabang
                        // -------------------------------------------------------
                        if($cabang != null) $location = $cabangColl->where('id', $row->cabang_id)->where('nama', $labelsArray[1])->first();
                        // -------------------------------------------------------
                        // Search wilayah
                        // -------------------------------------------------------
                        if($wilayah != null) $location = $wilayahColl->where('id', $row->wilayah_id)->where('nama', $labelsArray[1])->first();
                        // -------------------------------------------------------
                        // Search sub wilayah
                        // -------------------------------------------------------
                        if($subWilayah != null) $location = $subWilayahColl->where('id', $row->sub_wilayah_id)->where('nama', $labelsArray[1])->first();
                        // -------------------------------------------------------
                        if($row->tahun == $tahun && $row->pendidikan_id == $pendidikan->id && $location != null){
                            $total[] = (int)$row->total_jumlah;
                            $status = false;
                        }
                    }

                    if($status) $total[] = 0;
                    // -----------------------------------------------------------
                }
            }

            $result[] = [
                'label'             => $pendidikan->nama,
                'backgroundColor'   => randomColor(),
                'data'              => $total,
                'stack'             => 'stack_1'
            ];
        }
        // --------------------------------------------------------------------
        return $result;
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    private function setMonth($filterDate){
        $monthArray = [];
        // ----------------------------------------------------------------
        $firstYear  = Carbon::parse('01 '.$filterDate[0])->format('Y');
        $secondYear = Carbon::parse('01 '.$filterDate[1])->format('Y');
        // ----------------------------------------------------------------
        if($firstYear == $secondYear){
            // ------------------------------------------------------------
            $firstMonth     = Carbon::parse('01 '.$filterDate[0])->format('m');
            $secondMonth    = Carbon::parse('01 '.$filterDate[1])->format('m');
            // ------------------------------------------------------------
            for($i = $firstMonth - 1; $i < $secondMonth; $i++){
                $month = strlen($i) == 1 ? "0".$i+1 : $i+1;
                $monthArray[] = $month;
            }
            // ------------------------------------------------------------
        }elseif($firstYear < $secondYear){
            // ------------------------------------------------------------
            $firstMonth     = Carbon::parse('01 '.$filterDate[0])->format('m');
            $secondMonth    = Carbon::parse('01 '.$filterDate[0])->endOfYear()->format('m');
            // ------------------------------------------------------------
            for($i = $firstMonth - 1; $i < $secondMonth; $i++){
                $month = strlen($i) == 1 ? "0".$i+1 : $i+1;
                $monthArray[] = $month;
            }
            // ------------------------------------------------------------
            $firstMonth     = Carbon::parse('01 '.$filterDate[1])->startOfYear()->format('m');
            $secondMonth    = Carbon::parse('01 '.$filterDate[1])->format('m');
            // ------------------------------------------------------------
            for($i = $firstMonth - 1; $i < $secondMonth; $i++){
                $month = strlen($i) == 1 ? "0".$i+1 : $i+1;
                $monthArray[] = $month;
            }
            // ------------------------------------------------------------
        }
        // ----------------------------------------------------------------
        return $monthArray;
    }
    // ------------------------------------------------------------------------
}
