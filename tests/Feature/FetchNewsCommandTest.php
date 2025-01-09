<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use App\Models\Article;

class FetchNewsCommandTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test fetching news from The Guardian.
     */
    public function testFetchNewsFromGuardian()
    {
        // Mock The Guardian API response
        Http::fake([
            'https://content.guardianapis.com/*' => Http::response([
                'response' => [
                    'results' => [
                        [
                            'webTitle' => 'Guardian News Title',
                            'webUrl' => 'https://example.com/guardian-news1',
                            'fields' => ['byline' => 'Guardian Author'],
                            'webPublicationDate' => now()->toIso8601String(),
                        ]
                    ]
                ]
            ], 200),
        ]);

        // Run the command
        Artisan::call('fetch:guardian');

        // Assert the article is stored in the database
        $this->assertDatabaseHas('articles', [
            'title' => 'Guardian News Title',
            'source' => 'guardian'
        ]);
    }

    /**
     * Test fetching news from NYTimes.
     */
    public function testFetchNewsFromNytimes()
    {
        // Mock NYTimes API response
        Http::fake([
            'https://api.nytimes.com/svc/*' => Http::response([
                'results' => [
                    [
                        'title' => 'NYTimes News Title',
                        'url' => 'https://example.com/nytimes-news1',
                        'byline' => 'NYTimes Author',
                        'published_date' => now()->toIso8601String(),
                    ]
                ]
            ], 200),
        ]);

        // Run the command
        Artisan::call('fetch:nytimes');

        // Assert the article is stored in the database
        $this->assertDatabaseHas('articles', [
            'title' => 'NYTimes News Title',
            'source' => 'nytimes'
        ]);
    }

    /**
     * Test fetching news from NewsAPI.
     */
    public function testFetchNewsFromNewsApi()
    {
        // Mock NewsAPI response
        Http::fake([
            'https://newsapi.org/v2/*' => Http::response([
                'articles' => [
                    [
                        'source' => ['name' => 'NewsAPI'],
                        'author' => 'NewsAPI Author',
                        'title' => 'NewsAPI News Title',
                        'description' => 'NewsAPI News Description',
                        'url' => 'https://example.com/newsapi-news1',
                        'publishedAt' => now()->toIso8601String(),
                    ]
                ]
            ], 200),
        ]);

        // Run the command
        Artisan::call('fetch:newsapi');

        // Assert the article is stored in the database
        $this->assertDatabaseHas('articles', [
            'title' => 'NewsAPI News Title',
            'source' => 'newsapi'
        ]);
    }
}
