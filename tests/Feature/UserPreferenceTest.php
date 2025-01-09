<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Models\User;
use App\Models\Article;
use App\Models\UserPreference;

class UserPreferenceTest extends TestCase
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
     * Test setting user preferences.
     */
    public function testSetPreferences()
    {
        $this->authenticate();

        $data = [
            'sources' => ['guardian', 'nytimes'],
            'authors' => ['John Doe', 'Jane Smith'],
        ];

        $response = $this->postJson('/api/preferences', $data);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Preferences saved successfully.',
                'preferences' => $data,
            ]);


    }

    /**
     * Test retrieving user preferences.
     */
    public function testGetPreferences()
    {
        $user = $this->authenticate();

        // Seed preferences for the user
        UserPreference::factory()->create([
            'user_id' => $user->id,
            'sources' => ['guardian'],
            'authors' => ['John Doe'],
        ]);

        $response = $this->getJson('/api/preferences');

        $response->assertStatus(200)
            ->assertJson([
                'sources' => ['guardian'],
                'authors' => ['John Doe'],
            ]);
    }

    /**
     * Test retrieving preferences when none are set.
     */
    public function testGetPreferencesNotFound()
    {
        $this->authenticate();

        $response = $this->getJson('/api/preferences');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'No preferences found.',
            ]);
    }

    /**
     * Test fetching personalized feed.
     */
    public function testPersonalizedFeed()
    {
        $user = $this->authenticate();

        // Seed preferences for the user
        UserPreference::factory()->create([
            'user_id' => $user->id,
            'sources' => ['guardian'],
            'authors' => ['John Doe'],
        ]);

        // Seed articles
        Article::factory()->create([
            'title' => 'Tech Innovations',
            'source' => 'guardian',
            'author' => 'John Doe',
        ]);
        Article::factory()->create([
            'title' => 'Politics News',
            'source' => 'nytimes',
            'author' => 'Jane Smith',
        ]);

        $response = $this->getJson('/api/personalized-feed');

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Tech Innovations'])
            ->assertJsonMissing(['title' => 'Politics News']);
    }

    /**
     * Test fetching personalized feed when preferences are missing.
     */
    public function testPersonalizedFeedNoPreferences()
    {
        $this->authenticate();

        $response = $this->getJson('/api/personalized-feed');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'No preferences found.',
            ]);
    }

    /**
     * Test unauthorized access to preference routes.
     */
    public function testUnauthorizedAccess()
    {
        // Attempt to set preferences without authentication
        $response = $this->postJson('/api/preferences', [
            'sources' => ['guardian'],
            'authors' => ['John Doe'],
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);

        // Attempt to get preferences without authentication
        $response = $this->getJson('/api/preferences');

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);

        // Attempt to fetch personalized feed without authentication
        $response = $this->getJson('/api/personalized-feed');

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }
}
