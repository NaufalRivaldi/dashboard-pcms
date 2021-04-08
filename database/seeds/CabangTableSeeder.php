<?php
// ----------------------------------------------------------------------------
use Illuminate\Database\Seeder;
// ----------------------------------------------------------------------------
use App\Models\Cabang;
use App\Models\Wilayah;
use App\Models\SubWilayah;
// ----------------------------------------------------------------------------
use Carbon\Carbon;
// ----------------------------------------------------------------------------
class CabangTableSeeder extends Seeder
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
        Cabang::query()->forceDelete();
        // --------------------------------------------------------------------
        $wilayah = Wilayah::all();
        $subWilayah = SubWilayah::all();
        // --------------------------------------------------------------------

        $data = [
            [   
                'kode'              => 'CKP',
                'nama'              => 'CIKUPA',
                'status'            => 1,
                'wilayah_id'        => $wilayah->random()->id,
                'sub_wilayah_id'    => $subWilayah->random()->id,
                'user_id'           => 2,
            ],
            [   
                'kode'              => 'CMP',
                'nama'              => 'CEMPAKA PUTIH',
                'status'            => 1,
                'wilayah_id'        => $wilayah->random()->id,
                'sub_wilayah_id'    => $subWilayah->random()->id,
                'user_id'           => 2,
            ],
            [   
                'kode'              => 'CNR',
                'nama'              => 'CINERE',
                'status'            => 1,
                'wilayah_id'        => $wilayah->random()->id,
                'sub_wilayah_id'    => $subWilayah->random()->id,
                'user_id'           => 3,
            ],
            [   
                'kode'              => 'GDS',
                'nama'              => 'GADING SERPONG',
                'status'            => 1,
                'wilayah_id'        => $wilayah->random()->id,
                'sub_wilayah_id'    => $subWilayah->random()->id,
                'user_id'           => 2,
            ],
            [   
                'kode'              => 'MGA',
                'nama'              => 'MANGGA',
                'status'            => 1,
                'wilayah_id'        => $wilayah->random()->id,
                'sub_wilayah_id'    => $subWilayah->random()->id,
                'user_id'           => 3,
            ],
            [   
                'kode'              => 'TMG',
                'nama'              => 'TOMANG',
                'status'            => 1,
                'wilayah_id'        => $wilayah->random()->id,
                'sub_wilayah_id'    => $subWilayah->random()->id,
                'user_id'           => 3,
            ],
        ];
        // --------------------------------------------------------------------
        Cabang::insert($data);
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------
}

// ----------------------------------------------------------------------------