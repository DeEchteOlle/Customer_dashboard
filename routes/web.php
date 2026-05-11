<?php

use App\Http\Controllers\PagespeedController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WebsiteController;
use App\Models\Website;
use Illuminate\Support\Facades\Route;

// Alle app-routes vereisen een ingelogde gebruiker
Route::middleware('auth')->group(function () {

    Route::get('/', function () {
        $websites = Website::with('latestPagespeedResult')
            ->where('user_id', auth()->id())
            ->get();
        return view('pagespeed.results', compact('websites'));
    });

    Route::get('websites/{id}/results', [PagespeedController::class, 'viewResults'])->name('websites.results');
    Route::post('websites/{id}/scan', [PagespeedController::class, 'scan'])->name('websites.scan');
    Route::get('websites/{id}/export', [PagespeedController::class, 'exportCsv'])->name('websites.export');
    Route::post('pagespeed/run-all', [PagespeedController::class, 'runAll'])->name('pagespeed.runAll');

    Route::get('websites', [WebsiteController::class, 'index'])->name('websites.index');
    Route::get('websites/create', [WebsiteController::class, 'create']);
    Route::post('websites', [WebsiteController::class, 'store']);
    Route::get('websites/{id}/edit', [WebsiteController::class, 'edit']);
    Route::put('websites/{id}', [WebsiteController::class, 'update']);
    Route::delete('websites/{id}', [WebsiteController::class, 'delete']);

    // Breeze profielpagina
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';