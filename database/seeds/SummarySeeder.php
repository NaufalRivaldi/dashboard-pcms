<?php
// ----------------------------------------------------------------------------
use Illuminate\Database\Seeder;
// ----------------------------------------------------------------------------
use App\Models\Cabang;
use App\Models\Materi;
use App\Models\Pendidikan;
use App\Models\Summary;
use App\Models\SummarySAMateri;
use App\Models\SummarySAPendidikan;
// ----------------------------------------------------------------------------
use Faker\Generator as Faker;
use Carbon\Carbon;
// ----------------------------------------------------------------------------
class SummarySeeder extends Seeder
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
        // Set localization of faker
        // --------------------------------------------------------------------
        $faker = \Faker\Factory::create('id_ID');
        // --------------------------------------------------------------------
        SummarySAMateri::query()->delete();
        SummarySAPendidikan::query()->delete();
        Summary::query()->delete();
        // --------------------------------------------------------------------
        for($i = 0; $i < 12; $i++){
            $month = $i+1;
            $data = [
                'bulan' => strlen($month) == 1 ? "0".$month : $month,
                'tahun' => 2021,
                'uang_pendaftaran'  => $faker->numberBetween(500000, 1500000),
                'uang_kursus'       => $faker->numberBetween(30000000, 50000000),
                'siswa_aktif'       => $faker->numberBetween(30, 80),
                'siswa_baru'        => $faker->numberBetween(1, 20),
                'siswa_cuti'        => $faker->numberBetween(1, 10),
                'siswa_keluar'      => $faker->numberBetween(1, 5),
                'status'            => 1,
                'cabang_id'         => 1,
                'user_id'           => 5,
                'user_approve_id'   => 4,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ];

            Summary::insert($data);
        }
        // --------------------------------------------------------------------
        for($i = 0; $i < 12; $i++){
            $month = $i+1;
            $data = [
                'bulan' => strlen($month) == 1 ? "0".$month : $month,
                'tahun' => 2021,
                'uang_pendaftaran'  => $faker->numberBetween(500000, 1500000),
                'uang_kursus'       => $faker->numberBetween(30000000, 50000000),
                'siswa_aktif'       => $faker->numberBetween(30, 80),
                'siswa_baru'        => $faker->numberBetween(1, 20),
                'siswa_cuti'        => $faker->numberBetween(1, 10),
                'siswa_keluar'      => $faker->numberBetween(1, 5),
                'status'            => 1,
                'cabang_id'         => 3,
                'user_id'           => 5,
                'user_approve_id'   => 4,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ];

            Summary::insert($data);
        }
        // --------------------------------------------------------------------
        $summarys   = Summary::where('cabang_id', 1)->get();
        $materis    = Materi::all();
        foreach($summarys as $summary){
            foreach($materis as $materi){
                $data = [
                    'jumlah'            => $faker->numberBetween(20, 50),
                    'materi_id'         => $materi->id,
                    'summary_id'        => $summary->id,
                ];
    
                SummarySAMateri::insert($data);
            }
        }
        // --------------------------------------------------------------------
        $summarys   = Summary::where('cabang_id', 3)->get();
        $materis    = Materi::all();
        foreach($summarys as $summary){
            foreach($materis as $materi){
                $data = [
                    'jumlah'            => $faker->numberBetween(20, 50),
                    'materi_id'         => $materi->id,
                    'summary_id'        => $summary->id,
                ];
    
                SummarySAMateri::insert($data);
            }
        }
        // --------------------------------------------------------------------
        $summarys   = Summary::where('cabang_id', 1)->get();
        $pendidikans= Pendidikan::all();
        foreach($summarys as $summary){
            foreach($pendidikans as $pendidikan){
                $data = [
                    'jumlah'            => $faker->numberBetween(20, 50),
                    'pendidikan_id'     => $pendidikan->id,
                    'summary_id'        => $summary->id,
                ];
    
                SummarySAPendidikan::insert($data);
            }
        }
        // --------------------------------------------------------------------
        $summarys   = Summary::where('cabang_id', 3)->get();
        $pendidikans= Pendidikan::all();
        foreach($summarys as $summary){
            foreach($pendidikans as $pendidikan){
                $data = [
                    'jumlah'            => $faker->numberBetween(20, 50),
                    'pendidikan_id'     => $pendidikan->id,
                    'summary_id'        => $summary->id,
                ];
    
                SummarySAPendidikan::insert($data);
            }
        }
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------
}
// ----------------------------------------------------------------------------