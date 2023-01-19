<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin')->insert([
            'user_name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin'),
            'c_date' => Carbon::create('2022', '08', '02'),
            'role_id' => '1',
            
        ]);
    }
}
