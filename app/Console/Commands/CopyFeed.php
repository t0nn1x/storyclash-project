<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CopyFeed extends Command
{
    protected $signature = 'copy {id} {--only=} {--include-posts=}';
    protected $description = 'Copy feed entry with optional related entries';

    public function handle()
    {
        $id = $this->argument('id');
        $only = $this->option('only');
        $includePosts = $this->option('include-posts');

        // Use source connection
        $feed = DB::connection('source')->table('feeds')->find($id);
        if (!$feed) {
            $this->error('Feed not found.');
            return;
        }

        // Use destination connection
        $newFeedId = DB::connection('destination')->table('feeds')->insertGetId([
            'name' => $feed->name
        ]);

        // Copy related source entries
        if ($only) {
            if ($only == 'instagram') {
                $this->copyInstagramSources($feed->id, $newFeedId);
            } elseif ($only == 'tiktok') {
                $this->copyTiktokSources($feed->id, $newFeedId);
            } else {
                $this->error('Invalid source type.');
                return;
            }
        } else {
            // Copy all related source entries
            $this->copyInstagramSources($feed->id, $newFeedId);
            $this->copyTiktokSources($feed->id, $newFeedId);
        }

        // Copy related posts if --include-posts option is provided
        if ($includePosts) {
            $this->copyPosts($feed->id, $newFeedId, $includePosts);
        }

        $this->info('Feed copied successfully.');
    }

    protected function copyInstagramSources($oldFeedId, $newFeedId)
    {
        $instagramSources = DB::connection('source')->table('instagram_sources')->where('feed_id', $oldFeedId)->get();
        foreach ($instagramSources as $source) {
            DB::connection('destination')->table('instagram_sources')->insert([
                'feed_id' => $newFeedId,
                'name' => $source->name,
                'fan_count' => $source->fan_count
            ]);
        }
    }

    protected function copyTiktokSources($oldFeedId, $newFeedId)
    {
        $tiktokSources = DB::connection('source')->table('tiktok_sources')->where('feed_id', $oldFeedId)->get();
        foreach ($tiktokSources as $source) {
            DB::connection('destination')->table('tiktok_sources')->insert([
                'feed_id' => $newFeedId,
                'name' => $source->name,
                'fan_count' => $source->fan_count
            ]);
        }
    }

    protected function copyPosts($oldFeedId, $newFeedId, $count)
    {
        $posts = DB::connection('source')->table('posts')->where('feed_id', $oldFeedId)->take($count)->get();
        foreach ($posts as $post) {
            DB::connection('destination')->table('posts')->insert([
                'feed_id' => $newFeedId,
                'url' => $post->url
            ]);
        }
    }
}
