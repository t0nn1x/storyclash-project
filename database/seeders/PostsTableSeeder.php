<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $feed1 = DB::table('feeds')->where('name', 'Influencer 1')->first();
        $feed2 = DB::table('feeds')->where('name', 'Influencer 2')->first();
        $feed3 = DB::table('feeds')->where('name', 'Influencer 3')->first();

        DB::table('posts')->insert([
            ['feed_id' => $feed1->id, 'url' => 'http://example.com/post1'],
            ['feed_id' => $feed1->id, 'url' => 'http://example.com/post2'],
            ['feed_id' => $feed2->id, 'url' => 'http://example.com/post3'],
            ['feed_id' => $feed2->id, 'url' => 'http://example.com/post4'],
            ['feed_id' => $feed3->id, 'url' => 'http://example.com/post5']
        ]);
    }
}
