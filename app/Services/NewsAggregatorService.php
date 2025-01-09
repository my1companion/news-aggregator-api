<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Article;
use Carbon\Carbon;


class NewsAggregatorService
{
    public function fetchFromSource($sourceName, $url)
    {
        if($sourceName=='newsapi'){
            $response = Http::get($url, [
                'apiKey' => config("services.{$sourceName}.api_key"),
                'country'=>'us',
                'from'=>'2024-12-09',
                'sortBy'=>'publishedAt'
            ]);
            $articles = $response->json()['articles'] ?? [];
            foreach ($articles as $article) {
                $publishedAt = isset($article['publishedAt']) 
                    ? Carbon::parse($article['publishedAt'])->format('Y-m-d H:i:s')
                    : now();

                Article::updateOrCreate(
                    ['source_url' => $article['url']],
                    [
                        'title' => $article['title'],
                        'content' => $article['content'] ??'',
                        'author' => $article['author'] ?? null,
                        'source' => $sourceName,
                        'source_url' => $article['url'],
                        'published_at' => $publishedAt ?? now(),
                    ]
                );
            }

        }elseif($sourceName=='guardian'){
            $response = Http::get($url, [
                'api-key' => config("services.{$sourceName}.api_key"),
            ]);
            $articles = $response->json()['response']['results'] ?? [];

            foreach ($articles as $article) {
                $publishedAt = isset($article['webPublicationDate']) 
                    ? Carbon::parse($article['webPublicationDate'])->format('Y-m-d H:i:s')
                    : now();
                Article::updateOrCreate(
                    ['source_url' => $article['webUrl']],
                    [
                        'title' => $article['webTitle'],
                        'content' => $article['bodyText'] ??'',
                        'author' => $article['tags'] ?? '',
                        'source' => $sourceName,
                        'source_url' => $article['apiUrl'] ?? '',
                        'published_at' => $publishedAt ?? now(),
                    ]
                );
            }
        }else{

            $response = Http::get($url, [
                'api-key' => config("services.{$sourceName}.api_key"),
            ]);
            $articles = $response->json()['results'] ?? [];

            foreach ($articles as $article) {
                $publishedAt = isset($article['published_date']) 
                    ? Carbon::parse($article['published_date'])->format('Y-m-d H:i:s')
                    : now();
                Article::updateOrCreate(
                    ['source_url' => $article['url']],
                    [
                        'title' => $article['title'],
                        'content' => $article['abstract'] ??'',
                        'author' => $article['copyright'] ?? null,
                        'source' => $sourceName,
                        'source_url' => $article['url'],
                        'published_at' => $publishedAt ?? now(),
                    ]
                );
            }

        }

    }
}
