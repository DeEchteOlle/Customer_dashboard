<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        var_dump('Hello World');
    }
}
