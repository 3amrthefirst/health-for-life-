<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('currency')->insert([
            'name' => 'Pounds',
            'code' => 'IMP',
            'symbol' => '£',
            'status' =>'0',
            
        ]);
    }
}
