<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Models\PagespeedResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PagespeedController extends Controller
{

    public function viewResults($websiteId)
    {
        // Fetch the website and its associated PageSpeed results
        $website = Website::with('pagespeedResults')->find($websiteId);

        // Handle the case where the website is not found
        if (!$website) {
            abort(404, 'Website not found');
        }

        // Return the view with data
        return view('pagespeed.websiteResults', [
            'website' => $website,
            'pagespeedResults' => $website->pagespeedResults,
        ]);
    }

}

