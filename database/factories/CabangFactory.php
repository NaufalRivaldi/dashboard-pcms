<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
// ----------------------------------------------------------------------------
use App\Models\Cabang;
use App\Models\Wilayah;
use App\Models\SubWilayah;
use App\Models\User;
// ----------------------------------------------------------------------------
use Faker\Generator as Faker;
use Carbon\Carbon;
// ----------------------------------------------------------------------------
$factory->define(Cabang::class, function (Faker $faker) {
    // ------------------------------------------------------------------------
    // Set localization of faker
    // ------------------------------------------------------------------------
    $faker = \Faker\Factory::create('id_ID');
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    $data           = new \stdClass;
    $wilayahs       = Wilayah::where('status', 1)->get();
    $subWilayahs    = SubWilayah::where('status', 1)->get();
    $users          = User::where('level_id', 2)->get();
    // ------------------------------------------------------------------------
    $data->kode             = $faker->numerify('C####');
    $data->nama             = $faker->numerify('Cabang ##');
    $data->status           = 1;
    $data->wilayah_id       = $wilayahs->random()->id;
    $data->sub_wilayah_id   = $subWilayahs->random()->id;
    $data->user_id          = $users->random()->id;
    // ------------------------------------------------------------------------
    return (array) $data;
    // ------------------------------------------------------------------------
});
// ----------------------------------------------------------------------------