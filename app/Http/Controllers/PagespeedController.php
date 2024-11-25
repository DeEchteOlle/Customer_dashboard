<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PagespeedController extends Controller
{
    public function index(Request $request)
    {
        $websiteId = $request->input('website_id');
        $website = Website::with('pagespeedResults')->find($websiteId);

        if (!$website) {
            return response()->json(['message' => 'Website not found'], 404);
        }

        $url = $website->url;
        $key = env('APP_API_KEY');
        $apiUrl = "https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url={$url}&key={$key}";

        $response = Http::get($apiUrl);

        if ($response->successful()) {
            $data = $response->json();

            $website->pagespeedResults()->create(['website_url' => $url, 'performance_score' => $data['lcp']['inp']['cls']['fcp']['ttfb'] ?? null,]);

            return response()->json(['message' => 'PageSpeed results successfully saved']);
        }

        return response()->json(['message' => 'API call failed', 'error' => $response->body()], 500);
    }

    public function viewResults($websiteId)
    {
        $website = Website::with('pagespeedResults')->find($websiteId);

        if (!$website) {
            return response()->json(['message' => 'Website not found'], 404);
        }

        return view('dashboard', ['pagespeedResults' => $website->pagespeedResults]);
    }

}
