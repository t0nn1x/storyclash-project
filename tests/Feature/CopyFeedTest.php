<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Console\Commands\CopyFeed;

class CopyFeedTest extends TestCase
{
    use RefreshDatabase;

    public function testFeedNotFound()
    {
        $mock = Mockery::mock('alias:DB');

        // Mocking feed retrieval
        $mock->shouldReceive('connection')
            ->with('source')
            ->andReturn($mock);
        $mock->shouldReceive('table')
            ->with('feeds')
            ->andReturn($mock);
        $mock->shouldReceive('find')
            ->with(1)
            ->andReturn(null);

        $this->artisan('copy', ['id' => 1])
            ->expectsOutput('Feed not found.')
            ->assertExitCode(0);
    }

    public function testCopyFeedWithoutOptions()
    {
        $mock = Mockery::mock('alias:DB');

        // Mocking feed retrieval
        $mock->shouldReceive('connection')
            ->with('source')
            ->andReturn($mock);
        $mock->shouldReceive('table')
            ->with('feeds')
            ->andReturn($mock);
        $mock->shouldReceive('find')
            ->with(1)
            ->andReturn((object)['id' => 1, 'name' => 'Test Feed']);

        // Mocking feed insertion
        $mock->shouldReceive('connection')
            ->with('destination')
            ->andReturn($mock);
        $mock->shouldReceive('table')
            ->with('feeds')
            ->andReturn($mock);
        $mock->shouldReceive('insertGetId')
            ->andReturn(2);

        // Mocking Instagram source copying
        $mock->shouldReceive('connection')
            ->with('source')
            ->andReturn($mock);
        $mock->shouldReceive('table')
            ->with('instagram_sources')
            ->andReturn($mock);
        $mock->shouldReceive('where')
            ->with('feed_id', 1)
            ->andReturn($mock);
        $mock->shouldReceive('get')
            ->andReturn(collect([(object)['feed_id' => 1, 'name' => 'insta_handle', 'fan_count' => 1000]]));

        $mock->shouldReceive('connection')
            ->with('destination')
            ->andReturn($mock);
        $mock->shouldReceive('table')
            ->with('instagram_sources')
            ->andReturn($mock);
        $mock->shouldReceive('insert');

        // Mocking TikTok source copying
        $mock->shouldReceive('connection')
            ->with('source')
            ->andReturn($mock);
        $mock->shouldReceive('table')
            ->with('tiktok_sources')
            ->andReturn($mock);
        $mock->shouldReceive('where')
            ->with('feed_id', 1)
            ->andReturn($mock);
        $mock->shouldReceive('get')
            ->andReturn(collect([(object)['feed_id' => 1, 'name' => 'tiktok_handle', 'fan_count' => 2000]]));

        $mock->shouldReceive('connection')
            ->with('destination')
            ->andReturn($mock);
        $mock->shouldReceive('table')
            ->with('tiktok_sources')
            ->andReturn($mock);
        $mock->shouldReceive('insert');

        $this->artisan('copy', ['id' => 1])
            ->assertExitCode(0);
    }

    public function testCopyFeedWithOnlyInstagramSources()
    {
        $mock = Mockery::mock('alias:DB');

        // Mocking feed retrieval
        $mock->shouldReceive('connection')
            ->with('source')
            ->andReturn($mock);
        $mock->shouldReceive('table')
            ->with('feeds')
            ->andReturn($mock);
        $mock->shouldReceive('find')
            ->with(1)
            ->andReturn((object)['id' => 1, 'name' => 'Test Feed']);

        // Mocking feed insertion
        $mock->shouldReceive('connection')
            ->with('destination')
            ->andReturn($mock);
        $mock->shouldReceive('table')
            ->with('feeds')
            ->andReturn($mock);
        $mock->shouldReceive('insertGetId')
            ->andReturn(2);

        // Mocking Instagram source copying
        $mock->shouldReceive('connection')
            ->with('source')
            ->andReturn($mock);
        $mock->shouldReceive('table')
            ->with('instagram_sources')
            ->andReturn($mock);
        $mock->shouldReceive('where')
            ->with('feed_id', 1)
            ->andReturn($mock);
        $mock->shouldReceive('get')
            ->andReturn(collect([(object)['feed_id' => 1, 'name' => 'insta_handle', 'fan_count' => 1000]]));

        $mock->shouldReceive('connection')
            ->with('destination')
            ->andReturn($mock);
        $mock->shouldReceive('table')
            ->with('instagram_sources')
            ->andReturn($mock);
        $mock->shouldReceive('insert');

        $this->artisan('copy', ['id' => 1, '--only' => 'instagram'])
            ->assertExitCode(0);
    }

    public function testCopyFeedWithOnlyTikTokSources()
    {
        $mock = Mockery::mock('alias:DB');

        // Mocking feed retrieval
        $mock->shouldReceive('connection')
            ->with('source')
            ->andReturn($mock);
        $mock->shouldReceive('table')
            ->with('feeds')
            ->andReturn($mock);
        $mock->shouldReceive('find')
            ->with(1)
            ->andReturn((object)['id' => 1, 'name' => 'Test Feed']);

        // Mocking feed insertion
        $mock->shouldReceive('connection')
            ->with('destination')
            ->andReturn($mock);
        $mock->shouldReceive('table')
            ->with('feeds')
            ->andReturn($mock);
        $mock->shouldReceive('insertGetId')
            ->andReturn(2);

        // Mocking TikTok source copying
        $mock->shouldReceive('connection')
            ->with('source')
            ->andReturn($mock);
        $mock->shouldReceive('table')
            ->with('tiktok_sources')
            ->andReturn($mock);
        $mock->shouldReceive('where')
            ->with('feed_id', 1)
            ->andReturn($mock);
        $mock->shouldReceive('get')
            ->andReturn(collect([(object)['feed_id' => 1, 'name' => 'tiktok_handle', 'fan_count' => 2000]]));

        $mock->shouldReceive('connection')
            ->with('destination')
            ->andReturn($mock);
        $mock->shouldReceive('table')
            ->with('tiktok_sources')
            ->andReturn($mock);
        $mock->shouldReceive('insert');

        $this->artisan('copy', ['id' => 1, '--only' => 'tiktok'])
            ->assertExitCode(0);
    }

    public function testCopyFeedWithIncludePosts()
    {
        $mock = Mockery::mock('alias:DB');

        // Mocking feed retrieval
        $mock->shouldReceive('connection')
            ->with('source')
            ->andReturn($mock);
        $mock->shouldReceive('table')
            ->with('feeds')
            ->andReturn($mock);
        $mock->shouldReceive('find')
            ->with(1)
            ->andReturn((object)['id' => 1, 'name' => 'Test Feed']);

        // Mocking feed insertion
        $mock->shouldReceive('connection')
            ->with('destination')
            ->andReturn($mock);
        $mock->shouldReceive('table')
            ->with('feeds')
            ->andReturn($mock);
        $mock->shouldReceive('insertGetId')
            ->andReturn(2);

        // Mocking posts copying
        $mock->shouldReceive('connection')
            ->with('source')
            ->andReturn($mock);
        $mock->shouldReceive('table')
            ->with('posts')
            ->andReturn($mock);
        $mock->shouldReceive('where')
            ->with('feed_id', 1)
            ->andReturn($mock);
        $mock->shouldReceive('take')
            ->with(5)
            ->andReturn($mock);
        $mock->shouldReceive('get')
            ->andReturn(collect([(object)['feed_id' => 1, 'url' => 'http://example.com/post1']]));

        $mock->shouldReceive('connection')
            ->with('destination')
            ->andReturn($mock);
        $mock->shouldReceive('table')
            ->with('posts')
            ->andReturn($mock);
        $mock->shouldReceive('insert');

        $this->artisan('copy', ['id' => 1, '--include-posts' => 5])
            ->assertExitCode(0);
    }
}
