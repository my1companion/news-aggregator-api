<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserPreference;
use App\Models\Article;

class UserPreferenceController extends Controller
{
    /**
     * Set user preferences.
     */
    public function setPreferences(Request $request)
    {
        $validated = $request->validate([
            'sources' => 'array',
            'authors' => 'array',
        ]);

        $preference = UserPreference::updateOrCreate(
            ['user_id' => auth()->id()],
            $validated
        );

        return response()->json([
            'message' => 'Preferences saved successfully.',
            'preferences' => $preference,
        ], 200);
    }

    /**
     * Retrieve user preferences.
     */
    public function getPreferences()
    {
        $preference = UserPreference::where('user_id', auth()->id())->first();

        if (!$preference) {
            return response()->json([
                'message' => 'No preferences found.',
            ], 404);
        }

        return response()->json($preference, 200);
    }

    /**
     * Fetch personalized news feed.
     */
    public function personalizedFeed()
    {
        $preference = UserPreference::where('user_id', auth()->id())->first();

        if (!$preference) {
            return response()->json([
                'message' => 'No preferences found.',
            ], 404);
        }

        $query = Article::query();

        if ($preference->sources) {
            $query->whereIn('source', $preference->sources);
        }

        if ($preference->authors) {
            $query->whereIn('author', $preference->authors);
        }

        $articles = $query->paginate(10);

        return response()->json($articles, 200);
    }
}
