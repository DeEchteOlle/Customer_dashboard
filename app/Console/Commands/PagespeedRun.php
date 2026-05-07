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
    protected $description = 'Retrieve PageSpeed results and store them';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $key = config('services.pagespeed.api_key');

        if (empty($key)) {
            $this->error('PAGESPEED_API_KEY is not set in your .env file.');
            return 1;
        }

        $websites = Website::all();
        $totalSteps = $websites->count();

        if ($totalSteps === 0) {
            $this->warn('No websites found to analyze.');
            return;
        }

        // Create the progress bar
        $bar = $this->output->createProgressBar($totalSteps);
        $bar->start();

        foreach ($websites as $website) {
            $url = $website->url;

            // Call the Google PageSpeed Insights API
            $response = Http::timeout(500)->get('https://www.googleapis.com/pagespeedonline/v5/runPagespeed', [
                'url' => $url,
                'key' => $key,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Extract required metrics and convert to consistent units:
                // LCP/FCP/TTFB → seconds (API returns ms), CLS → decimal score (API returns ×100), INP → ms
                $rawLcp  = $data['loadingExperience']['metrics']['LARGEST_CONTENTFUL_PAINT_MS']['percentile'] ?? null;
                $rawInp  = $data['loadingExperience']['metrics']['INTERACTION_TO_NEXT_PAINT']['percentile'] ?? null;
                $rawCls  = $data['loadingExperience']['metrics']['CUMULATIVE_LAYOUT_SHIFT_SCORE']['percentile'] ?? null;
                $rawFcp  = $data['lighthouseResult']['audits']['first-contentful-paint']['numericValue'] ?? null;
                $rawTtfb = $data['lighthouseResult']['audits']['server-response-time']['numericValue'] ?? null;

                $metrics = [
                    'lcp'  => $rawLcp  !== null ? round($rawLcp / 1000, 3)  : null,
                    'inp'  => $rawInp,
                    'cls'  => $rawCls  !== null ? round($rawCls / 100, 3)   : null,
                    'fcp'  => $rawFcp  !== null ? round($rawFcp / 1000, 3)  : null,
                    'ttfb' => $rawTtfb !== null ? round($rawTtfb / 1000, 3) : null,
                ];

                // Store metrics in the database
                PagespeedResult::create([
                    'website_id' => $website->id,
                    'lcp' => $metrics['lcp'],
                    'inp' => $metrics['inp'],
                    'cls' => $metrics['cls'],
                    'fcp' => $metrics['fcp'],
                    'ttfb' => $metrics['ttfb'],
                ]);
            } else {
                $this->newLine();
                $this->error("Failed for: $url — HTTP {$response->status()}");
                $errorBody = $response->json('error.message') ?? $response->body();
                $this->line("  → $errorBody");
            }

            // Advance the progress bar
            $bar->advance();
        }

        // Finish the progress bar
        $bar->finish();
        $this->newLine();
        $this->info('All websites processed successfully!');
    }
}
