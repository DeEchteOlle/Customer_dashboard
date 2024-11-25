<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagespeed_results', function (Blueprint $table) {
            $table->id();
            $table->integer('lcp');
            $table->integer('inp');
            $table->integer('cls');
            $table->integer('fcp');
            $table->integer('ttfb');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagespeed_results');
    }
};
