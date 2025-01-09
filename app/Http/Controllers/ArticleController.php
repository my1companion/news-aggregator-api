<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
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
