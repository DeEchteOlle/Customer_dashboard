<?php

namespace App\Console\Commands;

use App\Models\PagespeedResult;
use App\Models\Website;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
    }
}
