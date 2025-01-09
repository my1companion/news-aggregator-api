<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
/**
 * @OA\Schema(
 *     schema="Article",
 *     type="object",
 *     title="Article",
 *     description="Schema for an article object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Breaking News"),
 *     @OA\Property(property="content", type="string", example="Details about the breaking news..."),
 *     @OA\Property(property="category", type="string", example="news"),
 *     @OA\Property(property="source", type="string", example="BBC"),
 *     @OA\Property(property="published_at", type="string", format="date-time", example="2023-01-01T10:00:00Z")
 * )
 */

class ArticleController extends Controller
{
    
    /**
     * @OA\Get(
     *     path="/api/articles",
     *     summary="Get a list of articles",
     *     tags={"Articles"},
     *     @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         description="Search articles by keyword (title or content)",
     *         required=false,
     *         @OA\Schema(type="string", example="technology")
     *     ),
     *     @OA\Parameter(
     *         name="published_at",
     *         in="query",
     *         description="Filter articles by publication date (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2023-01-01")
     *     ),
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         description="Filter articles by category",
     *         required=false,
     *         @OA\Schema(type="string", example="news")
     *     ),
     *     @OA\Parameter(
     *         name="source",
     *         in="query",
     *         description="Filter articles by source",
     *         required=false,
     *         @OA\Schema(type="string", example="BBC")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of articles per page",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="A paginated list of articles",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/Article")
     *             ),
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="last_page", type="integer", example=5),
     *             @OA\Property(property="total", type="integer", example=50)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'keyword' => 'nullable|string',
            'published_at' => 'nullable|date_format:Y-m-d',
            'category' => 'nullable|string',
            'source' => 'nullable|string',
            'per_page' => 'nullable|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Create a unique cache key based on the query parameters
        $cacheKey = 'articles_' . md5(http_build_query($request->all()));

        // Attempt to retrieve the cached result or query the database
        $articles = Cache::remember($cacheKey, 60, function () use ($request) {
            $query = Article::query();

            // Filter by source
            if ($request->has('source')) {
                $query->where('source', $request->source);
            }

            // Filter by published date
            if ($request->has('published_at')) {
                $query->whereDate('published_at', $request->published_at);
            }

            // Filter by keyword (title or content)
            if ($request->filled('keyword')) {
                $query->where(function($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->keyword . '%')
                      ->orWhere('content', 'like', '%' . $request->keyword . '%');
                });
            }

            // Filter by category
            if ($request->filled('category')) {
                $query->where('source', $request->category);
            }

            return $query->paginate(10);  // Pagination is important to cache only the current page
        });

        return response()->json($articles);
    }


    /**
     * @OA\Get(
     *     path="/api/articles/{id}",
     *     summary="Get a specific article by ID",
     *     tags={"Articles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the article to retrieve",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Details of the requested article",
     *         @OA\JsonContent(ref="#/components/schemas/Article")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Article not found.")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        // Check if the article is cached
        $article = Cache::remember("article_{$id}", 60, function () use ($id) {
            return Article::find($id);
        });

        // If the article is not found
        if (!$article) {
            return response()->json([
                'message' => 'Article not found.',
            ], 404);
        }

        return response()->json($article);
    }


    /**
     * @OA\Get(
     *     path="/api/articles/search",
     *     summary="Search for articles by a query string",
     *     tags={"Articles"},
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         description="Search query string",
     *         required=true,
     *         @OA\Schema(type="string", example="latest news")
     *     ),
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="A paginated list of search results",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/Article")
     *             ),
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="last_page", type="integer", example=3),
     *             @OA\Property(property="total", type="integer", example=30)
     *         )
     *     )
     * )
     */

    public function search(Request $request)
    {
        $query = $request->input('q');

        // Create a unique cache key for the search query
        $cacheKey = 'search_' . md5($query);

        // Attempt to retrieve the cached result or query the database
        $articles = Cache::remember($cacheKey, 60, function () use ($query) {
            return Article::whereFullText(['title', 'content'], $query)->paginate(10);
        });

        return response()->json($articles);
    }


}
