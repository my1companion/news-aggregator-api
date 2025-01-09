<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
