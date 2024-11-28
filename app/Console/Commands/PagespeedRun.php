<?php

namespace App\Console\Commands;

use App\Models\PagespeedResult;
use App\Models\Website;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class PagespeedRun extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pagespeed:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve Pagespeed results and store them';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $websites = Website::all();
        foreach ($websites->getIterator() as $website) {

            $url = $website->url;
            $key = env('PAGESPEED_API_KEY'); // Ensure your API key is in the .env file
            $apiUrl = "https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url={$url}&key={$key}";

            // Call the Google PageSpeed Insights API
            $response = Http::timeout(300)->get($apiUrl);

            if ($response->successful()) {
                $data = $response->json();

                // Extract required metrics
                $metrics = ['lcp' => $data['loadingExperience']['metrics']['LARGEST_CONTENTFUL_PAINT_MS']['percentile'] ?? null, 'inp' => $data['loadingExperience']['metrics']['INTERACTION_TO_NEXT_PAINT']['percentile'] ?? null, 'cls' => $data['loadingExperience']['metrics']['CUMULATIVE_LAYOUT_SHIFT_SCORE']['percentile'] ?? null, 'fcp' => $data['lighthouseResult']['audits']['first-contentful-paint']['numericValue'] ?? null, 'ttfb' => $data['lighthouseResult']['audits']['server-response-time']['numericValue'] ?? null,];

                // Store metrics in the database
                PagespeedResult::create(['website_id' => $website->id, 'lcp' => $metrics['lcp'], 'inp' => $metrics['inp'], 'cls' => $metrics['cls'], 'fcp' => $metrics['fcp'], 'ttfb' => $metrics['ttfb'],]);
            }
        }
    }
}

