<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsAggregatorService;

class FetchGuardianArticles extends Command
{
    protected $signature = 'fetch:guardian';
    protected $description = 'Fetch articles from The Guardian';

    public function handle()
    {
        $service = new NewsAggregatorService();
        $service->fetchFromSource('guardian', 'https://content.guardianapis.com/search');
        $this->info('Fetched articles from The Guardian.');
    }
}
