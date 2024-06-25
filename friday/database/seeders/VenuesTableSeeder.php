<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VenuesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $venues = [
            ['venue' => 'Th K'],
            ['venue' => 'Th J'],
            ['venue' => 'Th A'],
            ['venue' => 'Th B'],
            ['venue' => 'Th C'],
        ];

        DB::table('venues')->insert($venues);
    }
}
