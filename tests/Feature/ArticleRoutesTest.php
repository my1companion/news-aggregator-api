<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Models\User;
use App\Models\Article;

class ArticleRoutesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Authenticate a user using Sanctum.
     */
    protected function authenticate()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        return $user;
    }

    /**
     * Test fetching a paginated list of articles.
     */
    public function testListArticlesWithPagination()
    {
        $this->authenticate();

        // Create dummy articles
        Article::factory()->count(15)->create();

        // Send GET request
        $response = $this->getJson('/api/articles');

        // Assert status and structure
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'source',
                        'author',
                        'published_at',
                    ]
                ],
            ]);
    }

    /**
     * Test searching articles by keyword and source.
     */
    public function testSearchArticles()
    {
        $this->authenticate();

        // Create dummy articles
        Article::factory()->create([
            'title' => 'Tech Innovations',
            'source' => 'guardian',
        ]);
        Article::factory()->create([
            'title' => 'World Politics',
            'source' => 'nytimes',
        ]);

        // Search by keyword
        $response = $this->getJson('/api/articles?keyword=Tech');

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Tech Innovations'])
            ->assertJsonMissing(['title' => 'World Politics']);

        // Search by source
        $response = $this->getJson('/api/articles?source=nytimes');

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'World Politics'])
            ->assertJsonMissing(['title' => 'Tech Innovations']);
    }

    /**
     * Test fetching a single article's details.
     */
    public function testGetSingleArticle()
    {
        $this->authenticate();

        // Create a dummy article
        $article = Article::factory()->create([
            'title' => 'Sample Article',
            'content' => 'This is a sample article.',
            'author' => 'John Doe',
        ]);

        // Send GET request for the single article
        $response = $this->getJson("/api/articles/{$article->id}");

        // Assert status and structure
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'title',
                'content',
                'author',
                'source',
                'published_at',
            ])
            ->assertJsonFragment([
                'title' => 'Sample Article',
                'author' => 'John Doe',
            ]);
    }

    /**
     * Test retrieving a single article that doesn't exist.
     */
    public function testGetSingleArticleNotFound()
    {
        $this->authenticate();

        // Send GET request for a non-existent article
        $response = $this->getJson('/api/articles/999');

        // Assert status and error message
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Article not found.',
            ]);
    }

    /**
     * Test unauthorized access to articles route.
     */
    public function testUnauthorizedAccess()
    {
        // Send GET request without authentication
        $response = $this->getJson('/api/articles');

        // Assert unauthorized response
        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }
}
