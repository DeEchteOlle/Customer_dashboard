<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PagespeedController extends Controller
{
    public function index() {
//        $key = 'AIzaSyDDlvOhxLk6HM2R0i4h1Fq-4avYjuIV5NY';
//        $url = 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=https://adwise.nl&key=' . $key;
//        $response = Http::get($url);
//        return $response->json();
        Website::create([
            'url' => 'https://adwise.nl',
        ]);
    }
}
