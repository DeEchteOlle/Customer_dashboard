<?php

namespace App\Console\Commands;

use App\Mail\PerformanceAlert;
use App\Models\PagespeedResult;
use App\Models\Website;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

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
            foreach (['desktop', 'mobile'] as $strategy) {
                $response = Http::timeout(500)->get('https://www.googleapis.com/pagespeedonline/v5/runPagespeed', [
                    'url'      => $website->url,
                    'key'      => $key,
                    'strategy' => $strategy,
                ]);

                if ($response->successful()) {
                    $data = $response->json();

                    $rawLcp  = $data['loadingExperience']['metrics']['LARGEST_CONTENTFUL_PAINT_MS']['percentile'] ?? null;
                    $rawInp  = $data['loadingExperience']['metrics']['INTERACTION_TO_NEXT_PAINT']['percentile'] ?? null;
                    $rawCls  = $data['loadingExperience']['metrics']['CUMULATIVE_LAYOUT_SHIFT_SCORE']['percentile'] ?? null;
                    $rawFcp  = $data['lighthouseResult']['audits']['first-contentful-paint']['numericValue'] ?? null;
                    $rawTtfb = $data['lighthouseResult']['audits']['server-response-time']['numericValue'] ?? null;

                    $metrics = [
                        'lcp'  => $rawLcp  !== null ? round($rawLcp  / 1000, 3) : null,
                        'inp'  => $rawInp,
                        'cls'  => $rawCls  !== null ? round($rawCls  / 100,  3) : null,
                        'fcp'  => $rawFcp  !== null ? round($rawFcp  / 1000, 3) : null,
                        'ttfb' => $rawTtfb !== null ? round($rawTtfb / 1000, 3) : null,
                    ];

                    PagespeedResult::create([
                        'website_id' => $website->id,
                        'strategy'   => $strategy,
                        'lcp'  => $metrics['lcp'],
                        'inp'  => $metrics['inp'],
                        'cls'  => $metrics['cls'],
                        'fcp'  => $metrics['fcp'],
                        'ttfb' => $metrics['ttfb'],
                    ]);

                    // Controleer welke metrics de drempelwaarde overschrijden en stuur een alert
                    $alertTo = config('services.alerts.email');
                    if ($alertTo) {
                        $thresholds = [
                            'LCP'  => ['value' => $metrics['lcp'],  'threshold' => 2.5,  'unit' => 's'],
                            'INP'  => ['value' => $metrics['inp'],  'threshold' => 200,  'unit' => 'ms'],
                            'CLS'  => ['value' => $metrics['cls'],  'threshold' => 0.1,  'unit' => ''],
                            'FCP'  => ['value' => $metrics['fcp'],  'threshold' => 1.8,  'unit' => 's'],
                            'TTFB' => ['value' => $metrics['ttfb'], 'threshold' => 0.6,  'unit' => 's'],
                        ];

                        $failing = array_filter(
                            $thresholds,
                            fn($m) => $m['value'] !== null && $m['value'] > $m['threshold']
                        );

                        if (!empty($failing)) {
                            Mail::to($alertTo)->send(new PerformanceAlert($website, $failing));
                            $this->line("  → Alert verstuurd voor $strategy: " . implode(', ', array_keys($failing)));
                        }
                    }
                } else {
                    $this->newLine();
                    $this->error("Failed for: {$website->url} ($strategy) — HTTP {$response->status()}");
                    $this->line("  → " . ($response->json('error.message') ?? $response->body()));
                }
            }

            $bar->advance();
        }

        // Finish the progress bar
        $bar->finish();
        $this->newLine();
        $this->info('All websites processed successfully!');
    }
}
