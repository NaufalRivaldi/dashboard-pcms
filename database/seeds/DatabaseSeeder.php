<?php
// ----------------------------------------------------------------------------
use Illuminate\Database\Seeder;
// ----------------------------------------------------------------------------
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    // ------------------------------------------------------------------------
    public function run(){
        // --------------------------------------------------------------------
        // User Table and relation with user
        // --------------------------------------------------------------------
        $this->call(LevelTableSeeder::class);
        $this->call(UserTableSeeder::class);
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Cabang Table and relation with cabang
        // --------------------------------------------------------------------
        $this->call(SubWilayahTableSeeder::class);
        $this->call(WilayahTableSeeder::class);
        $this->call(CabangTableSeeder::class);
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Materi Table and relation with materi
        // --------------------------------------------------------------------
        $this->call(MateriTableSeeder::class);
        $this->call(GradeTableSeeder::class);
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Laporan seeder
        // --------------------------------------------------------------------
        $this->call(PendidikanTableSeeder::class);
        $this->call(SummarySeeder::class);
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------
}
// ----------------------------------------------------------------------------
