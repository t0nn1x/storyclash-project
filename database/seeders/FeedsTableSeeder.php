<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeedsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('feeds')->insert([
            ['name' => 'Influencer 1'],
            ['name' => 'Influencer 2'],
            ['name' => 'Influencer 3']
        ]);
    }
}
