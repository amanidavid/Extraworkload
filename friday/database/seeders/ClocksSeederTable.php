<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClocksSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $times = [
            ['clock' => '08:00:00'],
            ['clock' => '09:00:00'],
            ['clock' => '10:00:00'],
            ['clock' => '11:00:00'],
            ['clock' => '12:00:00'],
        ];

        DB::table('clocks')->insert($times);

    }
}
