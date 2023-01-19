<?php


namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Illuminate\Database\Seeder;

class SMTPSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('smtp')->insert([
            'protocol' => 'smtp',
            'host' => 'ssl://smtp.gmail.com',
            'port' => '465',
            'user' => 'admin@gmail.com',
            'pass' => ('123456'),
            'from_name' => 'DT_Docter',
            'from_email' => 'admin@gmail.com',
            'status' => '1',
            
            
        ]);
    }
}
