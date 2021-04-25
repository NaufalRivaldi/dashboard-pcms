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
        $filterDate[0] = viewDate(Carbon::now()->firstOfMonth()->subYears(1)->addMonth(1)->format('Y-m-d'));
        $filterDate[1] = viewDate(Carbon::now()->firstOfMonth()->format('Y-m-d'));
        // --------------------------------------------------------------------
        $item->filterDate = $filterDate;
        // --------------------------------------------------------------------
        $year = Carbon::now()->format('Y');
        $yearArray[0] = Carbon::now()->format('Y');
        $yearArray[1] = Carbon::now()->format('Y');
        // --------------------------------------------------------------------
        $data->cabang               = "ALL";
        // --------------------------------------------------------------------
        // If admin
        // --------------------------------------------------------------------
        if(Auth::user()->level_id == 1 || Auth::user()->level_id == 5){
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
        $data->dataSetPenerimaan    = $this->getDataSetPenerimaan($item->filterDate);
        $data->dataSetRoyalti       = $this->getDataSetRoyalti($item->filterDate);
        $data->dataSetSiswaAktif    = $this->getDataSetSiswaAktif($item->filterDate);
        $data->dataSetSiswaAktifJurusan    = $this->getDataSetSiswaAktifJurusan($item->filterDate);
        $data->dataSetSiswaAktifPendidikan = $this->getDataSetSiswaAktifPendidikan($item->filterDate);
        $data->labels               = $this->getLabel($item->filterDate);
        // --------------------------------------------------------------------
        return view('backend.analisa.self.index', (array) $data);
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
            $data->dataSetPenerimaan    = $this->getDataSetPenerimaan($filterDate, $request->cabang_id, $request->wilayah_id, $request->sub_wilayah_id, $filterYear);
            $data->dataSetRoyalti       = $this->getDataSetRoyalti($filterDate, $request->cabang_id, $request->wilayah_id, $request->sub_wilayah_id, $filterYear);
            $data->dataSetSiswaAktif    = $this->getDataSetSiswaAktif($filterDate, $request->cabang_id, $request->wilayah_id, $request->sub_wilayah_id, $filterYear);
            $data->dataSetSiswaAktifJurusan    = $this->getDataSetSiswaAktifJurusan($filterDate, $request->cabang_id, $request->wilayah_id, $request->sub_wilayah_id, $filterYear);
            $data->dataSetSiswaAktifPendidikan = $this->getDataSetSiswaAktifPendidikan($filterDate, $request->cabang_id, $request->wilayah_id, $request->sub_wilayah_id, $filterYear);
            $data->labels               = $this->getLabel($filterDate, $filterYear);
            // ----------------------------------------------------------------
            $data->status = true;
            $data->cabang = ($request->cabang_id != null ? strtoupper(Cabang::find($request->cabang_id)->nama) : ($request->wilayah_id == null && $request->sub_wilayah_id == null ? "ALL" : null ));
            $data->wilayah = $request->wilayah_id != null ? strtoupper(Wilayah::find($request->wilayah_id)->nama) : null;
            $data->sub_wilayah = $request->sub_wilayah_id != null ? strtoupper(SubWilayah::find($request->sub_wilayah_id)->nama) : null;
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
        $data->dataSetPenerimaan    = $this->getDataSetPenerimaan($filterDate, $request->cabang_id, $request->wilayah_id, $request->sub_wilayah_id, $filterYear);
        $data->dataSetRoyalti       = $this->getDataSetRoyalti($filterDate, $request->cabang_id, $request->wilayah_id, $request->sub_wilayah_id, $filterYear);
        $data->dataSetSiswaAktif    = $this->getDataSetSiswaAktif($filterDate, $request->cabang_id, $request->wilayah_id, $request->sub_wilayah_id, $filterYear);
        $data->dataSetSiswaAktifJurusan    = $this->getDataSetSiswaAktifJurusan($filterDate, $request->cabang_id, $request->wilayah_id, $request->sub_wilayah_id, $filterYear);
        $data->dataSetSiswaAktifPendidikan = $this->getDataSetSiswaAktifPendidikan($filterDate, $request->cabang_id, $request->wilayah_id, $request->sub_wilayah_id, $filterYear);
        $data->labels               = $this->getLabel($filterDate, $filterYear);
        // ----------------------------------------------------------------
        $data->cabang = ($request->cabang_id != null ? strtoupper(Cabang::find($request->cabang_id)->nama) : ($request->wilayah_id == null && $request->sub_wilayah_id == null ? "ALL" : null ));
        $data->wilayah = $request->wilayah_id != null ? strtoupper(Wilayah::find($request->wilayah_id)->nama) : null;
        $data->sub_wilayah = $request->sub_wilayah_id != null ? strtoupper(SubWilayah::find($request->sub_wilayah_id)->nama) : null;
        // ----------------------------------------------------------------
        return view('pdf.analisa-export', (array) $data);
    }

    // ------------------------------------------------------------------------
    public function getLabel($filterDate = null, $filterYear = null){
        // --------------------------------------------------------------------
        $labels = [];
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
        }else if($filterYear != null){
            for($i = $filterYear[0]; $i <= $filterYear[1]; $i++){
                $labels[] = $i;
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
        // Make condition if filter with date or year
        // --------------------------------------------------------------------
        if($filterDate != null){
            $month = $this->setMonth($filterDate);
            // ----------------------------------------------------------------
            $year[0] = Carbon::parse('01 '.$filterDate[0])->format('Y');
            $year[1] = Carbon::parse('01 '.$filterDate[1])->format('Y');
            // ----------------------------------------------------------------
            $query = Summary::selectRaw('bulan, tahun, SUM(uang_pendaftaran) as total_up, SUM(uang_kursus) as total_k')->where('status', 1);
        }else if($filterYear != null){
            $year = $filterYear;
            $query = Summary::selectRaw('tahun, SUM(uang_pendaftaran) as total_up, SUM(uang_kursus) as total_k')->where('status', 1);
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
                $query->where('cabang_id', $cabang);
            });
        }else if(Auth::user()->level_id == 2){
            $query->where(function($query){
                $query->whereHas('cabang', function($query){
                    $query->where('user_id', Auth::user()->id);
                    $query->where('status', 1);
                });
            });
        }else{
            $query->whereHas('cabang', function($query){
                $query->where('status', 1);
            });
        }
        // --------------------------------------------------------------------
        // Where wilayah
        // --------------------------------------------------------------------
        if($wilayah != null){
            $query->whereHas('cabang', function($query)use($wilayah){
                $query->where('wilayah_id', $wilayah);
                $query->where('status', 1);
                if(Auth::user()->level_id == 2){
                    $query->where('user_id', Auth::user()->id);
                }
            });
        }
        // --------------------------------------------------------------------
        // Where sub wilayah
        // --------------------------------------------------------------------
        if($subWilayah != null){
            $query->whereHas('cabang', function($query)use($subWilayah){
                $query->where('sub_wilayah_id', $subWilayah);
                $query->where('status', 1);
                if(Auth::user()->level_id == 2){
                    $query->where('user_id', Auth::user()->id);
                }
            });
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
            $query->groupBy('tahun', 'bulan');
        }else if($filterYear != null){
            $query->groupBy('tahun');
        }
        // -------------------------------------------------------------------
        $labels = $this->getLabel($filterDate, $filterYear);
        $totalPendaftaran = [];
        $totalKursus = [];
        $totalPenerimaan = [];
        if($filterDate != null){
            for($i = 0; $i < count($labels); $i++){
                // -----------------------------------------------------------
                $bulan = Carbon::parse('01 '.$labels[$i])->format('m');
                $tahun = Carbon::parse('01 '.$labels[$i])->format('Y');
                // -----------------------------------------------------------
                // Pendaftaran
                $status = true;
                foreach($query->get() as $row){
                    if($row->bulan == $bulan && $row->tahun == $tahun){
                        $totalPendaftaran[] = (int)$row->total_up;
                        $status = false;
                    }
                }

                if($status) $totalPendaftaran[] = 0;
                // -----------------------------------------------------------

                // Kursus
                $status = true;
                foreach($query->get() as $row){
                    if($row->bulan == $bulan && $row->tahun == $tahun){
                        $totalKursus[] = (int)$row->total_k;
                        $status = false;
                    }
                }

                if($status) $totalKursus[] = 0;
                // -----------------------------------------------------------

                // penerimaan
                $status = true;
                foreach($query->get() as $row){
                    if($row->bulan == $bulan && $row->tahun == $tahun){
                        $totalPenerimaan[] = (int)$row->total_k + (int)$row->total_up;
                        $status = false;
                    }
                }

                if($status) $totalPenerimaan[] = 0;
                // -----------------------------------------------------------
            }
        }else if($filterYear != null){
            for($i = 0; $i < count($labels); $i++){
                // -----------------------------------------------------------
                $status = true;
                foreach($query->get() as $row){
                    if($row->tahun == $labels[$i]){
                        $totalPendaftaran[] = (int)$row->total_up;
                        $status = false;
                    }
                }

                if($status) $totalPendaftaran[] = 0;
                // -----------------------------------------------------------

                // Kursus
                $status = true;
                foreach($query->get() as $row){
                    if($row->tahun == $labels[$i]){
                        $totalKursus[] = (int)$row->total_k;
                        $status = false;
                    }
                }

                if($status) $totalKursus[] = 0;
                // -----------------------------------------------------------

                // penerimaan
                $status = true;
                foreach($query->get() as $row){
                    if($row->tahun == $labels[$i]){
                        $totalPenerimaan[] = (int)$row->total_k + $row->total_up;
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
                'labels'            => ['London', 'New York', 'Paris', 'Moscow', 'Mumbai'],
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
        // Make condition if filter with date or year
        // --------------------------------------------------------------------
        if($filterDate != null){
            // ----------------------------------------------------------------
            $month = $this->setMonth($filterDate);
            // ----------------------------------------------------------------
            $year[0] = Carbon::parse('01 '.$filterDate[0])->format('Y');
            $year[1] = Carbon::parse('01 '.$filterDate[1])->format('Y');
            // ----------------------------------------------------------------
            $query = Summary::selectRaw('bulan, tahun, SUM(uang_pendaftaran) as total_up, SUM(uang_kursus) as total_k')->where('status', 1);
            // ----------------------------------------------------------------
        }else if($filterYear != null){
            $year = $filterYear;
            $query = Summary::selectRaw('tahun, SUM(uang_pendaftaran) as total_up, SUM(uang_kursus) as total_k')->where('status', 1);
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
                $query->where('cabang_id', $cabang);
            });
        }else if(Auth::user()->level_id == 2){
            $query->where(function($query){
                $query->whereHas('cabang', function($query){
                    $query->where('user_id', Auth::user()->id);
                });
            });
        }else{
            $query->whereHas('cabang', function($query){
                $query->where('status', 1);
            });
        }
        // --------------------------------------------------------------------
        // Where wilayah
        // --------------------------------------------------------------------
        if($wilayah != null){
            $query->whereHas('cabang', function($query)use($wilayah){
                $query->where('wilayah_id', $wilayah);
                $query->where('status', 1);
                if(Auth::user()->level_id == 2){
                    $query->where('user_id', Auth::user()->id);
                }
            });
        }
        // --------------------------------------------------------------------
        // Where sub wilayah
        // --------------------------------------------------------------------
        if($subWilayah != null){
            $query->whereHas('cabang', function($query)use($subWilayah){
                $query->where('sub_wilayah_id', $subWilayah);
                $query->where('status', 1);
                if(Auth::user()->level_id == 2){
                    $query->where('user_id', Auth::user()->id);
                }
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
        // Set array dataset
        // --------------------------------------------------------------------
        if($filterDate != null){
            $query->groupBy('tahun', 'bulan');
        }else if($filterYear != null){
            $query->groupBy('tahun');
        }
        // --------------------------------------------------------------------
        $labels = $this->getLabel($filterDate, $filterYear);
        $totalRoyalti = [];
        // Pendaftaran
        if($filterDate != null){
            for($i = 0; $i < count($labels); $i++){
                // -----------------------------------------------------------
                $bulan = Carbon::parse('01 '.$labels[$i])->format('m');
                $tahun = Carbon::parse('01 '.$labels[$i])->format('Y');
                // -----------------------------------------------------------
                // royalti
                $status = true;
                foreach($query->get() as $row){
                    if($row->bulan == $bulan && $row->tahun == $tahun){
                        $totalRoyalti[] = ((int)$row->total_k + (int)$row->total_up) * 0.1;
                        $status = false;
                    }
                }

                if($status) $totalRoyalti[] = 0;
                // -----------------------------------------------------------
            }
        }else if($filterYear != null){
            for($i = 0; $i < count($labels); $i++){
                // -----------------------------------------------------------
                // royalti
                $status = true;
                foreach($query->get() as $row){
                    if($row->tahun == $labels[$i]){
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
                'type'              => 'line',
                'tension'           => 0,
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
        // Make condition if filter with date or year
        // --------------------------------------------------------------------
        if($filterDate != null){
            $month = $this->setMonth($filterDate);
            // ----------------------------------------------------------------
            $year[0] = Carbon::parse('01 '.$filterDate[0])->format('Y');
            $year[1] = Carbon::parse('01 '.$filterDate[1])->format('Y');
            // ----------------------------------------------------------------
            $query = Summary::selectRaw('bulan, tahun, SUM(siswa_aktif) as total_sa, SUM(siswa_baru) as total_sb, SUM(siswa_cuti) as total_sc, SUM(siswa_keluar) as total_sk')->where('status', 1);
        }else if($filterYear != null){
            $year = $filterYear;
            $query = Summary::selectRaw('tahun, SUM(siswa_aktif) as total_sa, SUM(siswa_baru) as total_sb, SUM(siswa_cuti) as total_sc, SUM(siswa_keluar) as total_sk')->where('status', 1);
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
                $query->where('cabang_id', $cabang);
            });
        }else if(Auth::user()->level_id == 2){
            $query->where(function($query){
                $query->whereHas('cabang', function($query){
                    $query->where('user_id', Auth::user()->id);
                });
            });
        }else{
            $query->whereHas('cabang', function($query){
                $query->where('status', 1);
            });
        }
        // --------------------------------------------------------------------
        // Where wilayah
        // --------------------------------------------------------------------
        if($wilayah != null){
            $query->whereHas('cabang', function($query)use($wilayah){
                $query->where('wilayah_id', $wilayah);
                $query->where('status', 1);
                if(Auth::user()->level_id == 2){
                    $query->where('user_id', Auth::user()->id);
                }
            });
        }
        // --------------------------------------------------------------------
        // Where sub wilayah
        // --------------------------------------------------------------------
        if($subWilayah != null){
            $query->whereHas('cabang', function($query)use($subWilayah){
                $query->where('sub_wilayah_id', $subWilayah);
                $query->where('status', 1);
                if(Auth::user()->level_id == 2){
                    $query->where('user_id', Auth::user()->id);
                }
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
        // Set array dataset
        // --------------------------------------------------------------------
        if($filterDate != null){
            $query->groupBy('tahun', 'bulan');
        }else if($filterYear != null){
            $query->groupBy('tahun');
        }
        // --------------------------------------------------------------------
        $labels = $this->getLabel($filterDate, $filterYear);
        $totalSiswaAktif = [];
        $totalSiswaBaru = [];
        $totalSiswaCuti = [];
        $totalSiswaKeluar = [];
        // Pendaftaran
        if($filterDate != null){
            for($i = 0; $i < count($labels); $i++){
                // -----------------------------------------------------------
                $bulan = Carbon::parse('01 '.$labels[$i])->format('m');
                $tahun = Carbon::parse('01 '.$labels[$i])->format('Y');
                // -----------------------------------------------------------
                // Siswa aktif
                $status = true;
                foreach($query->get() as $row){
                    if($row->bulan == $bulan && $row->tahun == $tahun){
                        $totalSiswaAktif[] = (int)$row->total_sa;
                        $status = false;
                    }
                }

                if($status) $totalSiswaAktif[] = 0;
                // -----------------------------------------------------------
                // Siswa baru
                $status = true;
                foreach($query->get() as $row){
                    if($row->bulan == $bulan && $row->tahun == $tahun){
                        $totalSiswaBaru[] = (int)$row->total_sb;
                        $status = false;
                    }
                }

                if($status) $totalSiswaBaru[] = 0;
                // -----------------------------------------------------------
                // Siswa cuti
                $status = true;
                foreach($query->get() as $row){
                    if($row->bulan == $bulan && $row->tahun == $tahun){
                        $totalSiswaCuti[] = (int)$row->total_sc;
                        $status = false;
                    }
                }

                if($status) $totalSiswaCuti[] = 0;
                // -----------------------------------------------------------
                // Siswa keluar
                $status = true;
                foreach($query->get() as $row){
                    if($row->bulan == $bulan && $row->tahun == $tahun){
                        $totalSiswaKeluar[] = (int)$row->total_sk;
                        $status = false;
                    }
                }

                if($status) $totalSiswaKeluar[] = 0;
                // -----------------------------------------------------------
            }
        }else if($filterYear != null){
            for($i = 0; $i < count($labels); $i++){
                // -----------------------------------------------------------
                // Siswa aktif
                $status = true;
                foreach($query->get() as $row){
                    if($row->tahun == $labels[$i]){
                        $totalSiswaAktif[] = (int)$row->total_sa;
                        $status = false;
                    }
                }

                if($status) $totalSiswaAktif[] = 0;
                // -----------------------------------------------------------
                // Siswa baru
                $status = true;
                foreach($query->get() as $row){
                    if($row->tahun == $labels[$i]){
                        $totalSiswaBaru[] = (int)$row->total_sb;
                        $status = false;
                    }
                }

                if($status) $totalSiswaBaru[] = 0;
                // -----------------------------------------------------------
                // Siswa cuti
                $status = true;
                foreach($query->get() as $row){
                    if($row->tahun == $labels[$i]){
                        $totalSiswaCuti[] = (int)$row->total_sc;
                        $status = false;
                    }
                }

                if($status) $totalSiswaCuti[] = 0;
                // -----------------------------------------------------------
                // Siswa keluar
                $status = true;
                foreach($query->get() as $row){
                    if($row->tahun == $labels[$i]){
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
            ],
            [
                'label'             => 'Siswa Baru',
                'backgroundColor'   => '#d35400',
                'data'              => $totalSiswaBaru,
            ],
            [
                'label'             => 'Siswa Cuti',
                'backgroundColor'   => '#27ae60',
                'data'              => $totalSiswaCuti,
            ],
            [
                'label'             => 'Siswa Keluar',
                'backgroundColor'   => '#8e44ad',
                'data'              => $totalSiswaKeluar,
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
        // Make condition if filter with date or year
        // --------------------------------------------------------------------
        if($filterDate != null){
            $month = $this->setMonth($filterDate);
            // ----------------------------------------------------------------
            $year[0] = Carbon::parse('01 '.$filterDate[0])->format('Y');
            $year[1] = Carbon::parse('01 '.$filterDate[1])->format('Y');
            // ----------------------------------------------------------------
            $query = Summary::where('status', 1);
        }else if($filterYear != null){
            $year = $filterYear;
            $query = Summary::where('status', 1);
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
                $query->where('cabang_id', $cabang);
            });
        }else if(Auth::user()->level_id == 2){
            $query->where(function($query){
                $query->whereHas('cabang', function($query){
                    $query->where('user_id', Auth::user()->id);
                });
            });
        }else{
            $query->whereHas('cabang', function($query){
                $query->where('status', 1);
            });
        }
        // --------------------------------------------------------------------
        // Where wilayah
        // --------------------------------------------------------------------
        if($wilayah != null){
            $query->whereHas('cabang', function($query)use($wilayah){
                $query->where('wilayah_id', $wilayah);
                $query->where('status', 1);
                if(Auth::user()->level_id == 2){
                    $query->where('user_id', Auth::user()->id);
                }
            });
        }
        // --------------------------------------------------------------------
        // Where sub wilayah
        // --------------------------------------------------------------------
        if($subWilayah != null){
            $query->whereHas('cabang', function($query)use($subWilayah){
                $query->where('sub_wilayah_id', $subWilayah);
                $query->where('status', 1);
                if(Auth::user()->level_id == 2){
                    $query->where('user_id', Auth::user()->id);
                }
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
            $relationship = DB::table('summary_sa_materi')
                            ->selectRaw('SUM(summary_sa_materi.jumlah) as total_jumlah, materi.id as materi_id, summary.bulan, summary.tahun')
                            ->join('summary', 'summary_sa_materi.summary_id', '=', 'summary.id')
                            ->join('materi', 'summary_sa_materi.materi_id', '=', 'materi.id')
                            ->whereIn('summary_sa_materi.summary_id', $summaryArray)
                            ->orderBy('summary.tahun', 'asc')
                            ->orderBy('summary.bulan', 'asc')
                            ->groupBy('summary.tahun', 'summary.bulan', 'materi.id');
        }else if($filterYear != null){
            $relationship = DB::table('summary_sa_materi')
                            ->selectRaw('SUM(summary_sa_materi.jumlah) as total_jumlah, materi.id as materi_id, summary.tahun')
                            ->join('summary', 'summary_sa_materi.summary_id', '=', 'summary.id')
                            ->join('materi', 'summary_sa_materi.materi_id', '=', 'materi.id')
                            ->whereIn('summary_sa_materi.summary_id', $summaryArray)
                            ->orderBy('summary.tahun', 'asc')
                            ->groupBy('summary.tahun', 'materi.id');
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
            $labels = $this->getLabel($filterDate, $filterYear);
            $total = [];
            if($filterDate != null){
                for($i = 0; $i < count($labels); $i++){
                    // -----------------------------------------------------------
                    $bulan = Carbon::parse('01 '.$labels[$i])->format('m');
                    $tahun = Carbon::parse('01 '.$labels[$i])->format('Y');
                    // -----------------------------------------------------------
                    $status = true;
                    foreach($data as $row){
                        if($row->bulan == $bulan && $row->tahun == $tahun && $row->materi_id == $materi->id){
                            $total[] = (int)$row->total_jumlah;
                            $status = false;
                        }
                    }

                    if($status) $total[] = 0;
                    // -----------------------------------------------------------
                }
            }else if($filterYear != null){
                for($i = 0; $i < count($labels); $i++){
                    // -----------------------------------------------------------
                    // royalti
                    $status = true;
                    foreach($data as $row){
                        if($row->tahun == $labels[$i] && $row->materi_id == $materi->id){
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
        // Make condition if filter with date or year
        // --------------------------------------------------------------------
        if($filterDate != null){
            $month = $this->setMonth($filterDate);
            // ----------------------------------------------------------------
            $year[0] = Carbon::parse('01 '.$filterDate[0])->format('Y');
            $year[1] = Carbon::parse('01 '.$filterDate[1])->format('Y');
            // ----------------------------------------------------------------
            $query = Summary::where('status', 1);
        }else if($filterYear != null){
            $year = $filterYear;
            $query = Summary::where('status', 1);
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
                $query->where('cabang_id', $cabang);
            });
        }else if(Auth::user()->level_id == 2){
            $query->where(function($query){
                $query->whereHas('cabang', function($query){
                    $query->where('user_id', Auth::user()->id);
                });
            });
        }else{
            $query->whereHas('cabang', function($query){
                $query->where('status', 1);
            });
        }
        // --------------------------------------------------------------------
        // Where wilayah
        // --------------------------------------------------------------------
        if($wilayah != null){
            $query->whereHas('cabang', function($query)use($wilayah){
                $query->where('wilayah_id', $wilayah);
                $query->where('status', 1);
                if(Auth::user()->level_id == 2){
                    $query->where('user_id', Auth::user()->id);
                }
            });
        }
        // --------------------------------------------------------------------
        // Where sub wilayah
        // --------------------------------------------------------------------
        if($subWilayah != null){
            $query->whereHas('cabang', function($query)use($subWilayah){
                $query->where('sub_wilayah_id', $subWilayah);
                $query->where('status', 1);
                if(Auth::user()->level_id == 2){
                    $query->where('user_id', Auth::user()->id);
                }
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
            $relationship = DB::table('summary_sa_pendidikan')
                            ->selectRaw('SUM(summary_sa_pendidikan.jumlah) as total_jumlah, pendidikan.id as pendidikan_id, summary.bulan, summary.tahun')
                            ->join('summary', 'summary_sa_pendidikan.summary_id', '=', 'summary.id')
                            ->join('pendidikan', 'summary_sa_pendidikan.pendidikan_id', '=', 'pendidikan.id')
                            ->whereIn('summary_sa_pendidikan.summary_id', $summaryArray)
                            ->orderBy('summary.tahun', 'asc')
                            ->orderBy('summary.bulan', 'asc')
                            ->groupBy('summary.tahun', 'summary.bulan', 'pendidikan.id');
        }else if($filterYear != null){
            $relationship = DB::table('summary_sa_pendidikan')
                            ->selectRaw('SUM(summary_sa_pendidikan.jumlah) as total_jumlah, pendidikan.id as pendidikan_id, summary.tahun')
                            ->join('summary', 'summary_sa_pendidikan.summary_id', '=', 'summary.id')
                            ->join('pendidikan', 'summary_sa_pendidikan.pendidikan_id', '=', 'pendidikan.id')
                            ->whereIn('summary_sa_pendidikan.summary_id', $summaryArray)
                            ->orderBy('summary.tahun', 'asc')
                            ->groupBy('summary.tahun', 'pendidikan.id');
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
            $labels = $this->getLabel($filterDate, $filterYear);
            $total = [];
            if($filterDate != null){
                for($i = 0; $i < count($labels); $i++){
                    // -----------------------------------------------------------
                    $bulan = Carbon::parse('01 '.$labels[$i])->format('m');
                    $tahun = Carbon::parse('01 '.$labels[$i])->format('Y');
                    // -----------------------------------------------------------
                    $status = true;
                    foreach($data as $row){
                        if($row->bulan == $bulan && $row->tahun == $tahun && $row->pendidikan_id == $pendidikan->id){
                            $total[] = (int)$row->total_jumlah;
                            $status = false;
                        }
                    }

                    if($status) $total[] = 0;
                    // -----------------------------------------------------------
                }
            }else if($filterYear != null){
                for($i = 0; $i < count($labels); $i++){
                    // -----------------------------------------------------------
                    // royalti
                    $status = true;
                    foreach($data as $row){
                        if($row->tahun == $labels[$i] && $row->pendidikan_id == $pendidikan->id){
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
