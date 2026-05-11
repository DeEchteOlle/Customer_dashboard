<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pagespeed_results', function (Blueprint $table) {
            $table->string('strategy', 10)->default('desktop')->after('website_id');
        });
    }

    public function down(): void
    {
        Schema::table('pagespeed_results', function (Blueprint $table) {
            $table->dropColumn('strategy');
        });
    }
};
