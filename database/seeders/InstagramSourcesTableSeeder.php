<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstagramSourcesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $feed1 = DB::table('feeds')->where('name', 'Influencer 1')->first();
        $feed2 = DB::table('feeds')->where('name', 'Influencer 2')->first();

        DB::table('instagram_sources')->insert([
            ['feed_id' => $feed1->id, 'name' => 'insta_handle1', 'fan_count' => 1000],
            ['feed_id' => $feed2->id, 'name' => 'insta_handle2', 'fan_count' => 2000]
        ]);
    }
}
