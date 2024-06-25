<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class WdaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $days = [
            ['weekday_name' => 'Monday'],
            ['weekday_name' => 'Tuesday'],
            ['weekday_name' => 'Wednesday'],
            ['weekday_name' => 'Thursday'],
            ['weekday_name' => 'Friday'],
            ['weekday_name' => 'Saturday'],
            ['weekday_name' => 'Sunday'],
        ];

        DB::table('wdays')->insert($days);
    }
    
}
