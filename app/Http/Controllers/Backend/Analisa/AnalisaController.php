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
// ----------------------------------------------------------------------------
class AnalisaController extends Controller
{
    // ------------------------------------------------------------------------
    public function index()
    {
        // --------------------------------------------------------------------
        $data = new \stdClass; $item = new \stdClass;
        $data->title        = "Analisa";
        $data->item = $item;
        // --------------------------------------------------------------------
        // Init data
        // --------------------------------------------------------------------
        $filterDate[0] = viewDate(Carbon::now()->startOfYear()->format('Y-m-d'));
        $filterDate[1] = viewDate(Carbon::now()->endOfYear()->format('Y-m-d'));
        // --------------------------------------------------------------------
        $item->filterDate = $filterDate;
        // --------------------------------------------------------------------
        $year = Carbon::now()->format('Y');
        $yearArray[0] = Carbon::now()->format('Y');
        $yearArray[1] = Carbon::now()->format('Y');
        // --------------------------------------------------------------------
        $data->cabang               = "ALL";
        $data->cabangs              = Cabang::where('status', 1)->pluck('nama', 'id');
        $data->dataSetPenerimaan    = $this->getDataSetPenerimaan($item->filterDate);
        $data->dataSetRoyalti       = $this->getDataSetRoyalti($item->filterDate);
        $data->dataSetSiswaAktif    = $this->getDataSetSiswaAktif($item->filterDate);
        $data->dataSetSiswaAktifJurusan    = $this->getDataSetSiswaAktifJurusan($item->filterDate);
        $data->dataSetSiswaAktifPendidikan = $this->getDataSetSiswaAktifPendidikan($item->filterDate);
        $data->labels               = $this->getLabel($item->filterDate);
        // --------------------------------------------------------------------
        return view('backend.analisa.index', (array) $data);
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
        // Use try catch
        // --------------------------------------------------------------------
        try {
            // ----------------------------------------------------------------
            $filterDate[0] = $request->startDate;
            $filterDate[1] = $request->endDate;
            // ----------------------------------------------------------------
            $data->dataSetPenerimaan    = $this->getDataSetPenerimaan($filterDate, $request->cabang_id);
            $data->dataSetRoyalti       = $this->getDataSetRoyalti($filterDate, $request->cabang_id);
            $data->dataSetSiswaAktif    = $this->getDataSetSiswaAktif($filterDate, $request->cabang_id);
            $data->dataSetSiswaAktifJurusan    = $this->getDataSetSiswaAktifJurusan($filterDate, $request->cabang_id);
            $data->dataSetSiswaAktifPendidikan = $this->getDataSetSiswaAktifPendidikan($filterDate, $request->cabang_id);
            $data->labels               = $this->getLabel($filterDate);
            // ----------------------------------------------------------------
            $data->status = true;
            $data->cabang = $request->cabang_id != null ? Cabang::find($request->cabang_id) : 'ALL';
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

    // ------------------------------------------------------------------------
    public function getLabel($filterDate = null){
        // --------------------------------------------------------------------
        $labels = [];
        // --------------------------------------------------------------------
        if($filterDate == null){
            // ----------------------------------------------------------------
            $year = Carbon::now()->format('Y');
            // ----------------------------------------------------------------
            for($i = 0; $i < 12; $i++){
                $month = strlen($i) == 1 ? "0".$i+1 : $i+1;
                $labels[] = Carbon::parse($year.'-'.$month.'-01')->format('M Y');
            }
            // ----------------------------------------------------------------
        }else{
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
                    $labels[] = Carbon::parse($firstYear.'-'.$month.'-01')->format('M Y');
                }
                // ------------------------------------------------------------
            }elseif($firstYear < $secondYear){
                // ------------------------------------------------------------
                $firstMonth     = Carbon::parse('01 '.$filterDate[0])->format('m');
                $secondMonth    = Carbon::parse('01 '.$filterDate[0])->endOfYear()->format('m');
                // ------------------------------------------------------------
                for($i = $firstMonth - 1; $i < $secondMonth; $i++){
                    $month = strlen($i) == 1 ? "0".$i+1 : $i+1;
                    $labels[] = Carbon::parse($firstYear.'-'.$month.'-01')->format('M Y');
                }
                // ------------------------------------------------------------
                $firstMonth     = Carbon::parse('01 '.$filterDate[1])->startOfYear()->format('m');
                $secondMonth    = Carbon::parse('01 '.$filterDate[1])->format('m');
                // ------------------------------------------------------------
                for($i = $firstMonth - 1; $i < $secondMonth; $i++){
                    $month = strlen($i) == 1 ? "0".$i+1 : $i+1;
                    $labels[] = Carbon::parse($secondYear.'-'.$month.'-01')->format('M Y');
                }
                // ------------------------------------------------------------
            }
            // ----------------------------------------------------------------
        }
        // --------------------------------------------------------------------
        return $labels;
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    // Get data penerimaan summery (Chart data)
    // ------------------------------------------------------------------------
    public function getDataSetPenerimaan($filterDate, $cabang = null){
        // --------------------------------------------------------------------
        $month = $this->setMonth($filterDate);
        // --------------------------------------------------------------------
        $year[0] = Carbon::parse('01 '.$filterDate[0])->format('Y');
        $year[1] = Carbon::parse('01 '.$filterDate[1])->format('Y');
        // --------------------------------------------------------------------
        $query = Summary::selectRaw('bulan, tahun, SUM(uang_pendaftaran) as total_up, SUM(uang_kursus) as total_k')->where('status', 1);
        // --------------------------------------------------------------------
        // Where Month
        // --------------------------------------------------------------------
        $query->where(function($query)use($month){
            foreach($month as $row){
                $month_format = strlen($row) == 1 ? "0".$row : $row;
                $query->orWhere('bulan', $month_format);
            }
        });
        // --------------------------------------------------------------------
        // Where Year
        // --------------------------------------------------------------------
        $query->whereBetween('tahun', $year);
        // --------------------------------------------------------------------
        // Where Cabang
        // --------------------------------------------------------------------
        if($cabang != null){
            $query->where(function($query)use($cabang){
                $query->where('cabang_id', $cabang);
            });
        }
        // --------------------------------------------------------------------
        // $query->orderBy('tahun', 'desc')->orderBy('bulan', 'asc');
        // --------------------------------------------------------------------
        $query->orderBy('tahun', 'asc');
        $query->orderBy('bulan', 'asc');
        // --------------------------------------------------------------------
        // Set array dataset
        // --------------------------------------------------------------------
        $data = $query->groupBy('tahun', 'bulan')->get();
        // -------------------------------------------------------------------
        // Pendaftaran
        $totalPendaftaran = [];
        foreach($query->get() as $row){
            $totalPendaftaran[] = $row->total_up;
        }

        // Kursus
        $totalKursus = [];
        foreach($query->get() as $row){
            $totalKursus[] = $row->total_k;
        }

        // penerimaan
        $totalPenerimaan = [];
        foreach($query->get() as $row){
            $totalPenerimaan[] = $row->total_k + $row->total_up;
        }

        // Result
        $result = [
            [
                'label' => 'Uang Pendaftaran',
                'backgroundColor' => '#3498db',
                'data' => $totalPendaftaran,
            ],
            [
                'label' => 'Uang Kursus',
                'backgroundColor' => '#1abc9c',
                'data' => $totalKursus,
            ],
            [
                'label' => 'Total Penerimaan',
                'backgroundColor' => '#f39c12',
                'data' => $totalPenerimaan,
                'type' => 'line',
                'fill' => false,
                'borderColor' => '#f39c12',
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
    public function getDataSetRoyalti($filterDate, $cabang = null){
        // --------------------------------------------------------------------
        $month = $this->setMonth($filterDate);
        // --------------------------------------------------------------------
        $year[0] = Carbon::parse('01 '.$filterDate[0])->format('Y');
        $year[1] = Carbon::parse('01 '.$filterDate[1])->format('Y');
        // --------------------------------------------------------------------
        $cabang = $cabang == null ? [] : $cabang;
        // --------------------------------------------------------------------
        $query = Summary::selectRaw('bulan, tahun, SUM(uang_pendaftaran) as total_up, SUM(uang_kursus) as total_k')->where('status', 1);
        // --------------------------------------------------------------------
        // Where Month
        // --------------------------------------------------------------------
        $query->where(function($query)use($month){
            foreach($month as $row){
                $month_format = strlen($row) == 1 ? "0".$row : $row;
                $query->orWhere('bulan', $month_format);
            }
        });
        // --------------------------------------------------------------------
        // Where Year
        // --------------------------------------------------------------------
        $query->whereBetween('tahun', $year);
        // --------------------------------------------------------------------
        // Where Cabang
        // --------------------------------------------------------------------
        if($cabang != null){
            $query->where('cabang_id', $cabang);
        }
        // --------------------------------------------------------------------
        // $query->orderBy('tahun', 'desc')->orderBy('bulan', 'asc');
        // --------------------------------------------------------------------
        $query->orderBy('tahun', 'asc');
        $query->orderBy('bulan', 'asc');
        // --------------------------------------------------------------------
        // Set array dataset
        // --------------------------------------------------------------------
        $data = $query->groupBy('tahun', 'bulan')->get();
        // --------------------------------------------------------------------
        // royalti
        $totalRoyalti = [];
        foreach($query->get() as $row){
            $totalRoyalti[] = ($row->total_k + $row->total_up) * 0.1;
        }

        // Result
        $result = [
            [
                'label' => 'Royalti',
                'backgroundColor' => '#74b9ff',
                'data' => $totalRoyalti,
                'type' => 'line',
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
    public function getDataSetSiswaAktif($filterDate, $cabang = null){
        // --------------------------------------------------------------------
        $month = $this->setMonth($filterDate);
        // --------------------------------------------------------------------
        $year[0] = Carbon::parse('01 '.$filterDate[0])->format('Y');
        $year[1] = Carbon::parse('01 '.$filterDate[1])->format('Y');
        // --------------------------------------------------------------------
        $cabang = $cabang == null ? [] : $cabang;
        // --------------------------------------------------------------------
        $query = Summary::selectRaw('bulan, tahun, SUM(siswa_aktif) as total_sa, SUM(siswa_baru) as total_sb, SUM(siswa_cuti) as total_sc, SUM(siswa_keluar) as total_sk')->where('status', 1);
        // --------------------------------------------------------------------
        // Where Month
        // --------------------------------------------------------------------
        $query->where(function($query)use($month){
            foreach($month as $row){
                $month_format = strlen($row) == 1 ? "0".$row : $row;
                $query->orWhere('bulan', $month_format);
            }
        });
        // --------------------------------------------------------------------
        // Where Year
        // --------------------------------------------------------------------
        $query->whereBetween('tahun', $year);
        // --------------------------------------------------------------------
        // Where Cabang
        // --------------------------------------------------------------------
        if($cabang != null){
            $query->where('cabang_id', $cabang);
        }
        // --------------------------------------------------------------------
        // $query->orderBy('tahun', 'desc')->orderBy('bulan', 'asc');
        // --------------------------------------------------------------------
        $query->orderBy('tahun', 'asc');
        $query->orderBy('bulan', 'asc');
        // --------------------------------------------------------------------
        // Set array dataset
        // --------------------------------------------------------------------
        $data = $query->groupBy('tahun', 'bulan')->get();
        // --------------------------------------------------------------------
        // Siswa aktif
        $totalSiswaAktif = [];
        foreach($query->get() as $row){
            $totalSiswaAktif[] = $row->total_sa;
        }

        // Siswa baru
        $totalSiswaBaru = [];
        foreach($query->get() as $row){
            $totalSiswaBaru[] = $row->total_sb;
        }

        // Siswa cuti
        $totalSiswaCuti = [];
        foreach($query->get() as $row){
            $totalSiswaCuti[] = $row->total_sc;
        }

        // Siswa keluar
        $totalSiswaKeluar = [];
        foreach($query->get() as $row){
            $totalSiswaKeluar[] = $row->total_sk;
        }

        // Result
        $result = [
            [
                'label' => 'Siswa Aktif',
                'backgroundColor' => '#3498db',
                'data' => $totalSiswaAktif,
            ],
            [
                'label' => 'Siswa Baru',
                'backgroundColor' => '#d35400',
                'data' => $totalSiswaBaru,
            ],
            [
                'label' => 'Siswa Cuti',
                'backgroundColor' => '#27ae60',
                'data' => $totalSiswaCuti,
            ],
            [
                'label' => 'Siswa Keluar',
                'backgroundColor' => '#8e44ad',
                'data' => $totalSiswaKeluar,
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
    public function getDataSetSiswaAktifJurusan($filterDate, $cabang = null){
        // --------------------------------------------------------------------
        $month = $this->setMonth($filterDate);
        // --------------------------------------------------------------------
        $year[0] = Carbon::parse('01 '.$filterDate[0])->format('Y');
        $year[1] = Carbon::parse('01 '.$filterDate[1])->format('Y');
        // --------------------------------------------------------------------
        $cabang = $cabang == null ? [] : $cabang;
        // --------------------------------------------------------------------
        $query = Summary::where('status', 1);
        // --------------------------------------------------------------------
        // Where Month
        // --------------------------------------------------------------------
        $query->where(function($query)use($month){
            foreach($month as $row){
                $month_format = strlen($row) == 1 ? "0".$row : $row;
                $query->orWhere('bulan', $month_format);
            }
        });
        // --------------------------------------------------------------------
        // Where Year
        // --------------------------------------------------------------------
        $query->whereBetween('tahun', $year);
        // --------------------------------------------------------------------
        // Where Cabang
        // --------------------------------------------------------------------
        if($cabang != null){
            $query->where('cabang_id', $cabang);
        }
        // --------------------------------------------------------------------
        // $query->orderBy('tahun', 'desc')->orderBy('bulan', 'asc');
        // --------------------------------------------------------------------
        $query->orderBy('tahun', 'asc');
        $query->orderBy('bulan', 'asc');
        // --------------------------------------------------------------------
        $summaryArray = $query->pluck('id')->toArray();
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Get data summary sa jurusan
        // --------------------------------------------------------------------
        $relationship = DB::table('summary_sa_materi')
                            ->selectRaw('SUM(summary_sa_materi.jumlah) as total_jumlah, materi.id as materi_id, summary.bulan, summary.tahun')
                            ->join('summary', 'summary_sa_materi.summary_id', '=', 'summary.id')
                            ->join('materi', 'summary_sa_materi.materi_id', '=', 'materi.id')
                            ->whereIn('summary_sa_materi.summary_id', $summaryArray)
                            ->orderBy('summary.tahun', 'asc')
                            ->orderBy('summary.bulan', 'asc')
                            ->groupBy('summary.tahun', 'summary.bulan', 'materi.id');
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Set array dataset
        // --------------------------------------------------------------------
        $data = $relationship->get();
        // --------------------------------------------------------------------
        $result = [];
        $materis = Materi::where('status', 1)->get();
        foreach($materis as $materi){
            $total = [];
            foreach($data as $row){
                if($row->materi_id == $materi->id) $total[] = $row->total_jumlah;
            }

            $result[] = [
                'label' => $materi->nama,
                'backgroundColor' => randomColor(),
                'data' => $total,
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
    public function getDataSetSiswaAktifpendidikan($filterDate, $cabang = null){
        // --------------------------------------------------------------------
        $month = $this->setMonth($filterDate);
        // --------------------------------------------------------------------
        $year[0] = Carbon::parse('01 '.$filterDate[0])->format('Y');
        $year[1] = Carbon::parse('01 '.$filterDate[1])->format('Y');
        // --------------------------------------------------------------------
        $cabang = $cabang == null ? [] : $cabang;
        // --------------------------------------------------------------------
        $query = Summary::where('status', 1);
        // --------------------------------------------------------------------
        // Where Month
        // --------------------------------------------------------------------
        $query->where(function($query)use($month){
            foreach($month as $row){
                $month_format = strlen($row) == 1 ? "0".$row : $row;
                $query->orWhere('bulan', $month_format);
            }
        });
        // --------------------------------------------------------------------
        // Where Year
        // --------------------------------------------------------------------
        $query->whereBetween('tahun', $year);
        // --------------------------------------------------------------------
        // Where Cabang
        // --------------------------------------------------------------------
        if($cabang != null){
            $query->where('cabang_id', $cabang);
        }
        // --------------------------------------------------------------------
        // $query->orderBy('tahun', 'desc')->orderBy('bulan', 'asc');
        // --------------------------------------------------------------------
        $query->orderBy('tahun', 'asc');
        $query->orderBy('bulan', 'asc');
        // --------------------------------------------------------------------
        $summaryArray = $query->pluck('id')->toArray();
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Get data summary sa pendidikan
        // --------------------------------------------------------------------
        $relationship = DB::table('summary_sa_pendidikan')
                            ->selectRaw('SUM(summary_sa_pendidikan.jumlah) as total_jumlah, pendidikan.id as pendidikan_id, summary.bulan, summary.tahun')
                            ->join('summary', 'summary_sa_pendidikan.summary_id', '=', 'summary.id')
                            ->join('pendidikan', 'summary_sa_pendidikan.pendidikan_id', '=', 'pendidikan.id')
                            ->whereIn('summary_sa_pendidikan.summary_id', $summaryArray)
                            ->orderBy('summary.tahun', 'asc')
                            ->orderBy('summary.bulan', 'asc')
                            ->groupBy('summary.tahun', 'summary.bulan', 'pendidikan.id');
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Set array dataset
        // --------------------------------------------------------------------
        $data = $relationship->get();
        // --------------------------------------------------------------------
        $result = [];
        $pendidikans = Pendidikan::all();
        foreach($pendidikans as $pendidikan){
            $total = [];
            foreach($data as $row){
                if($row->pendidikan_id == $pendidikan->id) $total[] = $row->total_jumlah;
            }

            $result[] = [
                'label' => $pendidikan->nama,
                'backgroundColor' => randomColor(),
                'data' => $total,
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
