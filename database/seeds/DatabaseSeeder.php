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
        $this->call(GradeTableSeeder::class);
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------
}
// ----------------------------------------------------------------------------
