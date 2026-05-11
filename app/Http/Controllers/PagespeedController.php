<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Models\PagespeedResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;

class PagespeedController extends Controller
{
    public function viewResults($websiteId, Request $request)
    {
        $website = Website::where('user_id', auth()->id())->findOrFail($websiteId);
        $strategy = in_array($request->query('strategy'), ['desktop', 'mobile']) ? $request->query('strategy') : 'desktop';

        // Laatste 30 voor grafieken (asc = links→rechts in tijd)
        $pagespeedResults = $website->pagespeedResults()
            ->where('strategy', $strategy)
            ->orderBy('created_at', 'asc')
            ->take(30)
            ->get();

        // Alle metingen voor de historietabel (nieuwste bovenaan)
        $allResults = $website->pagespeedResults()
            ->where('strategy', $strategy)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pagespeed.websiteResults', compact('website', 'pagespeedResults', 'allResults', 'strategy'));
    }

    public function scan($websiteId)
    {
        $website = Website::where('user_id', auth()->id())->findOrFail($websiteId);
        $key = config('services.pagespeed.api_key');

        if (empty($key)) {
            return back()->with('error', 'PAGESPEED_API_KEY is niet ingesteld.');
        }

        foreach (['desktop', 'mobile'] as $strategy) {
            $response = Http::timeout(60)->get('https://www.googleapis.com/pagespeedonline/v5/runPagespeed', [
                'url'      => $website->url,
                'key'      => $key,
                'strategy' => $strategy,
            ]);

            if (!$response->successful()) continue;

            $data = $response->json();
            $rawLcp  = $data['loadingExperience']['metrics']['LARGEST_CONTENTFUL_PAINT_MS']['percentile'] ?? null;
            $rawInp  = $data['loadingExperience']['metrics']['INTERACTION_TO_NEXT_PAINT']['percentile'] ?? null;
            $rawCls  = $data['loadingExperience']['metrics']['CUMULATIVE_LAYOUT_SHIFT_SCORE']['percentile'] ?? null;
            $rawFcp  = $data['lighthouseResult']['audits']['first-contentful-paint']['numericValue'] ?? null;
            $rawTtfb = $data['lighthouseResult']['audits']['server-response-time']['numericValue'] ?? null;

            PagespeedResult::create([
                'website_id' => $website->id,
                'strategy'   => $strategy,
                'lcp'  => $rawLcp  !== null ? round($rawLcp  / 1000, 3) : null,
                'inp'  => $rawInp,
                'cls'  => $rawCls  !== null ? round($rawCls  / 100, 3)  : null,
                'fcp'  => $rawFcp  !== null ? round($rawFcp  / 1000, 3) : null,
                'ttfb' => $rawTtfb !== null ? round($rawTtfb / 1000, 3) : null,
            ]);
        }

        return redirect()->route('websites.results', $websiteId)->with('success', 'Scan voltooid voor desktop én mobiel.');
    }

    public function runAll()
    {
        // Artisan::call voert het command synchroon uit — de pagina laadt pas
        // als alle websites gescand zijn. Bij veel websites kan dit even duren.
        Artisan::call('pagespeed:run');

        return redirect('/')->with('success', 'Scan voor alle websites voltooid.');
    }

    public function exportCsv($websiteId)
    {
        $website = Website::where('user_id', auth()->id())->findOrFail($websiteId);
        $results = $website->pagespeedResults()->orderBy('created_at', 'desc')->get();

        $filename = str($website->name)->slug() . '-pagespeed.csv';
        $headers  = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"$filename\""];

        return response()->stream(function () use ($results) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Datum', 'Strategie', 'LCP (s)', 'INP (ms)', 'CLS', 'FCP (s)', 'TTFB (s)']);
            foreach ($results as $row) {
                fputcsv($handle, [$row->created_at->format('Y-m-d H:i:s'), $row->strategy, $row->lcp, $row->inp, $row->cls, $row->fcp, $row->ttfb]);
            }
            fclose($handle);
        }, 200, $headers);
    }
}