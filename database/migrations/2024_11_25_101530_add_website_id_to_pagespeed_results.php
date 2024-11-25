<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pagespeed_results', function (Blueprint $table) {
            $table->foreignId('website_id')->references('id')->on('websites')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::table('pagespeed_results', function (Blueprint $table) {
            $table->dropColumn('website_id');
        });
    }
};
