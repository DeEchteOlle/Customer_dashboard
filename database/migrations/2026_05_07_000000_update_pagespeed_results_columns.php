<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pagespeed_results', function (Blueprint $table) {
            $table->float('lcp')->nullable()->change();
            $table->float('inp')->nullable()->change();
            $table->float('cls')->nullable()->change();
            $table->float('fcp')->nullable()->change();
            $table->float('ttfb')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pagespeed_results', function (Blueprint $table) {
            $table->integer('lcp')->nullable(false)->change();
            $table->integer('inp')->nullable(false)->change();
            $table->integer('cls')->nullable(false)->change();
            $table->integer('fcp')->nullable(false)->change();
            $table->integer('ttfb')->nullable(false)->change();
        });
    }
};