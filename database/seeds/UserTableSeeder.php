<?php
// ----------------------------------------------------------------------------
use Illuminate\Database\Seeder;
// ----------------------------------------------------------------------------
use App\Models\User;
// ----------------------------------------------------------------------------
use Carbon\Carbon;
// ----------------------------------------------------------------------------
class UserTableSeeder extends Seeder
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
        User::query()->delete();
        // --------------------------------------------------------------------
        $data = [
            [   
                'nama'              => 'Admin',
                'username'          => 'admin',
                'email'             => 'admin@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password'          => bcrypt('123456'),
                'status'            => 1,
                'level_id'          => 1,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'nama'              => 'Owner',
                'username'          => 'owner',
                'email'             => 'owner@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password'          => bcrypt('123456'),
                'status'            => 1,
                'level_id'          => 2,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'nama'              => 'Owner2',
                'username'          => 'owner2',
                'email'             => 'owner2@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password'          => bcrypt('123456'),
                'status'            => 1,
                'level_id'          => 2,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'nama'              => 'Approver',
                'username'          => 'approver',
                'email'             => 'approver@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password'          => bcrypt('123456'),
                'status'            => 1,
                'level_id'          => 3,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'nama'              => 'User1',
                'username'          => 'user1',
                'email'             => 'user1@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password'          => bcrypt('123456'),
                'status'            => 1,
                'level_id'          => 4,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'nama'              => 'User2',
                'username'          => 'user2',
                'email'             => 'user2@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password'          => bcrypt('123456'),
                'status'            => 1,
                'level_id'          => 4,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
        ];
        // --------------------------------------------------------------------
        User::insert($data);
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------
}
// ----------------------------------------------------------------------------