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
                'latitude'          => '-6.208419215652845',
                'longitude'         => '106.81380259088797',
                'status'            => 1,
                'wilayah_id'        => $wilayah->random()->id,
                'sub_wilayah_id'    => $subWilayah->random()->id,
                'user_id'           => 2,
            ],
            [   
                'kode'              => 'CMP',
                'nama'              => 'CEMPAKA PUTIH',
                'latitude'          => '-6.172921866231976',
                'longitude'         => '106.99576364383383',
                'status'            => 1,
                'wilayah_id'        => $wilayah->random()->id,
                'sub_wilayah_id'    => $subWilayah->random()->id,
                'user_id'           => 2,
            ],
            [   
                'kode'              => 'CNR',
                'nama'              => 'CINERE',
                'latitude'          => '-6.879817001471318',
                'longitude'         => '108.07968895337865',
                'status'            => 1,
                'wilayah_id'        => $wilayah->random()->id,
                'sub_wilayah_id'    => $subWilayah->random()->id,
                'user_id'           => 3,
            ],
            [   
                'kode'              => 'GDS',
                'nama'              => 'GADING SERPONG',
                'latitude'          => '-3.133501397086677	',
                'longitude'         => '104.92138616446992',
                'status'            => 1,
                'wilayah_id'        => $wilayah->random()->id,
                'sub_wilayah_id'    => $subWilayah->random()->id,
                'user_id'           => 2,
            ],
            [   
                'kode'              => 'MGA',
                'nama'              => 'MANGGA',
                'latitude'          => '-6.128333227586295',
                'longitude'         => '106.00481690536819',
                'status'            => 1,
                'wilayah_id'        => $wilayah->random()->id,
                'sub_wilayah_id'    => $subWilayah->random()->id,
                'user_id'           => 3,
            ],
            [   
                'kode'              => 'TMG',
                'nama'              => 'TOMANG',
                'latitude'          => '-7.224702847203945',
                'longitude'         => '112.29075941152317	',
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