<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserPreference;
use App\Models\Article;

class UserPreferenceController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/user/preferences",
     *     summary="Set user preferences",
     *     tags={"User Preferences"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="sources", type="array", 
     *                 @OA\Items(type="string", example="BBC")
     *             ),
     *             @OA\Property(property="authors", type="array", 
     *                 @OA\Items(type="string", example="John Doe")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Preferences saved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Preferences saved successfully."),
     *             @OA\Property(property="preferences", type="object",
     *                 @OA\Property(property="sources", type="array", @OA\Items(type="string", example="BBC")),
     *                 @OA\Property(property="authors", type="array", @OA\Items(type="string", example="John Doe"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/user/preferences",
     *     summary="Get user preferences",
     *     tags={"User Preferences"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Retrieve user preferences",
     *         @OA\JsonContent(
     *             @OA\Property(property="sources", type="array", @OA\Items(type="string", example="BBC")),
     *             @OA\Property(property="authors", type="array", @OA\Items(type="string", example="John Doe"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No preferences found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No preferences found.")
     *         )
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/user/personalized-feed",
     *     summary="Fetch personalized news feed",
     *     tags={"User Preferences"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="A paginated list of personalized articles",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/Article")
     *             ),
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="last_page", type="integer", example=3),
     *             @OA\Property(property="total", type="integer", example=30)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No preferences found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No preferences found.")
     *         )
     *     )
     * )
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
