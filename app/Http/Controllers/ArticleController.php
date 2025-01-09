<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
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
            'per_page' => 'nullable|numeric|min:1', // Ensure 'per_page' is a number and greater than 0
        ]);

        // If validation fails, return an error response
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $articles = Article::query();

        // Filter by source
        if ($request->has('source')) {
            $articles->where('source', $request->source);
        }

        // Filter by date
        if ($request->has('date')) {
            $articles->whereDate('published_at', $request->date);
        }

        // Filter by keyword (title or content)
        if ($request->filled('keyword')) {
            $articles->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->keyword . '%')
                  ->orWhere('content', 'like', '%' . $request->keyword . '%');
            });
        }

        // Filter by published date
        if ($request->filled('published_at')) {
            $articles->whereDate('published_at', $request->published_at);
        }

        // Filter by category (section or source)
        if ($request->filled('category')) {
            $articles->where('source', $request->category);
        }


        return response()->json($articles->paginate(10));
    }

    public function show($id)
    {
        $article = Article::find($id);
        
        // if article not found
        if($article){
            return response()->json($article);
        }else{

            return response()->json([
            'message' => 'Article not found.',
        ], 404);

        }
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        // $articles = Article::where('title', 'LIKE', "%{$query}%")
            // ->orWhere('content', 'LIKE', "%{$query}%")
            // ->paginate(10);
        $articles = Article::whereFullText(['title', 'content'], $query)->paginate(10);
        return response()->json($articles);
    }
}
