<?php
// ----------------------------------------------------------------------------
use Illuminate\Database\Seeder;
// ----------------------------------------------------------------------------
use App\Models\SubWilayah;
// ----------------------------------------------------------------------------
use Carbon\Carbon;
// ----------------------------------------------------------------------------
class SubWilayahTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    // ------------------------------------------------------------------------
    public function run()
    {
        // --------------------------------------------------------------------
        SubWilayah::query()->forceDelete();
        // --------------------------------------------------------------------
        $data = [
            [   
                'kode'              => '0109',
                'nama'              => 'Jakarta Pusat',
                'status'            => 1,
            ],
            [   
                'kode'              => '0101',
                'nama'              => 'Jakarta Barat',
                'status'            => 1,
            ],
            [   
                'kode'              => '0107',
                'nama'              => 'Depok',
                'status'            => 1,
            ],
            [   
                'kode'              => '0102',
                'nama'              => 'Tanggerang',
                'status'            => 1,
            ],
            [   
                'kode'              => '0201',
                'nama'              => 'Bandung',
                'status'            => 1,
            ],
        ];
        // --------------------------------------------------------------------
        SubWilayah::insert($data);
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------
}
// ----------------------------------------------------------------------------