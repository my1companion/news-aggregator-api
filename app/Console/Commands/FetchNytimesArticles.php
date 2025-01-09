<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsAggregatorService;

class FetchNytimesArticles extends Command
{
    protected $signature = 'fetch:nytimes';
    protected $description = 'Fetch articles from New York Times';

    public function handle()
    {
        $service = new NewsAggregatorService();
        $service->fetchFromSource('nytimes', 'https://api.nytimes.com/svc/topstories/v2/home.json');
        $this->info('Fetched articles from New York Times.');
    }
}
