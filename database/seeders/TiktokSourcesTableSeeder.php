<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiktokSourcesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $feed1 = DB::table('feeds')->where('name', 'Influencer 1')->first();
        $feed3 = DB::table('feeds')->where('name', 'Influencer 3')->first();

        DB::table('tiktok_sources')->insert([
            ['feed_id' => $feed1->id, 'name' => 'tiktok_handle1', 'fan_count' => 3000],
            ['feed_id' => $feed3->id, 'name' => 'tiktok_handle3', 'fan_count' => 4000]
        ]);
    }
}
