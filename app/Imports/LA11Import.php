<?php
// ----------------------------------------------------------------------------
namespace App\Imports;
// ----------------------------------------------------------------------------
use App\Models\VWSiswaCuti;
use Maatwebsite\Excel\Concerns\ToModel;
// ----------------------------------------------------------------------------
use Carbon\Carbon;
// ----------------------------------------------------------------------------
class LA11Import implements ToModel
{
    private $rows = 0;

    public function __construct($fileName){
        $this->fileName = $fileName;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        ++$this->rows;
        // return new VWSiswaCuti([
        //     //
        // ]);
    }
    // ------------------------------------------------------------------------
    public function getRowCount(): int
    {
        return $this->rows;
    }
    // ------------------------------------------------------------------------
    public function kodeCabang(){
        $valArray = explode('-', $this->fileName);
        // --------------------------------------------------------------------
        return $valArray[0];
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    public function date(){
        $valArray = explode('-', $this->fileName);
        $date = str_replace(".CSV", "", $valArray[2]).'01';
        
        // --------------------------------------------------------------------
        return [
            Carbon::parse($date)->format('Y'),
            Carbon::parse($date)->format('m'),
        ];
    }
    // ------------------------------------------------------------------------
}
// ----------------------------------------------------------------------------