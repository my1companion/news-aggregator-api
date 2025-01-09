<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Article extends Model
{
    use HasFactory;
    //
     protected $fillable = [
        'title',           // The title of the article
        'content',         // The main content of the article
        'author',          // The author of the article
        'source',          // The source of the article (e.g., NewsAPI, Guardian, NYTimes)
        'source_url',      // The URL to the original article
        'published_at',    // The published date of the article
    ];

    protected $hidden = [
        'created_at',
        'updated_at'

    ];
    
    protected static function booted()
    {
        static::saved(function ($article) {
            // Clear the cached article
            Cache::forget("article_{$article->id}");
            // Clear the cached list of articles
            Cache::forget('articles');
        });

        static::deleted(function ($article) {
            // Clear the cached article
            Cache::forget("article_{$article->id}");
            // Clear the cached list of articles
            Cache::forget('articles');
        });
    }
}
