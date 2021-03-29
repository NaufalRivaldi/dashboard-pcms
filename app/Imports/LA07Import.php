<?php
// ----------------------------------------------------------------------------
namespace App\Imports;
// ----------------------------------------------------------------------------
use App\Models\VWSiswaAktifPendidikan;
use Maatwebsite\Excel\Concerns\ToModel;
// ----------------------------------------------------------------------------
use Carbon\Carbon;
// ----------------------------------------------------------------------------
class LA07Import implements ToModel
{
    public function __construct($fileName){
        $this->fileName = $fileName;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    // ------------------------------------------------------------------------
    public function model(array $row)
    {
        // --------------------------------------------------------------------
        $data   = new \stdClass; $rowArray = explode(';', str_replace('"', '', $row[0]));
        $date   = $this->date($this->fileName);
        // --------------------------------------------------------------------
        $data->bulan            = $date[1];
        $data->tahun            = $date[0];
        $data->cabang           = $this->kodeCabang($this->fileName);
        $data->pendidikan       = $rowArray[1];
        $data->jumlah           = $rowArray[$date[1]+1];
        // --------------------------------------------------------------------
        return new VWSiswaAktifPendidikan((array)$data);
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    public function kodeCabang($val){
        $valArray = explode('-', $val);
        // --------------------------------------------------------------------
        return $valArray[0];
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    public function date($val){
        $valArray = explode('-', $val);
        $date = str_replace(".CSV", "", $valArray[2]).'01';
        
        // --------------------------------------------------------------------
        return [
            Carbon::parse($date)->format('Y'),
            Carbon::parse($date)->format('m'),
        ];
    }
    // ------------------------------------------------------------------------
}
