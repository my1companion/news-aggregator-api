<?php

namespace Database\Factories;

use App\Models\UserPreference;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class UserPreferenceFactory extends Factory
{
    protected $model = UserPreference::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(), // Assuming there's a User model and you want to link the user
            'sources' => json_encode([
                'source1' => $this->faker->word,
                'source2' => $this->faker->word,
            ]), // Generate a JSON object for sources
            'authors' => json_encode([
                'author1' => $this->faker->name,
                'author2' => $this->faker->name,
            ]), // Generate a JSON object for authors
        ];
    }
}
