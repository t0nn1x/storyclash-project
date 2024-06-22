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

        $feed = $this->getFeedFromSource($id);
        if (!$feed) {
            $this->error('Feed not found.');
            return;
        }

        $newFeedId = $this->copyFeedToDestination($feed);

        $this->copyRelatedSources($feed->id, $newFeedId, $only);

        if ($includePosts) {
            $this->copyPosts($feed->id, $newFeedId, $includePosts);
        }

        $this->info('Feed copied successfully.');
    }

    protected function getFeedFromSource($id)
    {
        return DB::connection('source')->table('feeds')->find($id);
    }

    protected function copyFeedToDestination($feed)
    {
        return DB::connection('destination')->table('feeds')->insertGetId([
            'name' => $feed->name
        ]);
    }

    protected function copyRelatedSources($oldFeedId, $newFeedId, $only)
    {
        if ($only) {
            $this->copySpecificSources($oldFeedId, $newFeedId, $only);
        } else {
            $this->copyAllSources($oldFeedId, $newFeedId);
        }
    }

    protected function copySpecificSources($oldFeedId, $newFeedId, $only)
    {
        switch ($only) {
            case 'instagram':
                $this->copyInstagramSources($oldFeedId, $newFeedId);
                break;
            case 'tiktok':
                $this->copyTiktokSources($oldFeedId, $newFeedId);
                break;
            default:
                $this->error('Invalid source type.');
                break;
        }
    }

    protected function copyAllSources($oldFeedId, $newFeedId)
    {
        $this->copyInstagramSources($oldFeedId, $newFeedId);
        $this->copyTiktokSources($oldFeedId, $newFeedId);
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
