<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsAggregatorService;

class FetchNewsApiArticles extends Command
{
    protected $signature = 'fetch:newsapi';
    protected $description = 'Fetch articles from NewsAPI';

    public function handle()
    {
        $service = new NewsAggregatorService();
        $service->fetchFromSource('newsapi', 'https://newsapi.org/v2/top-headlines');
        $this->info('Fetched articles from NewsAPI.');
    }
}
