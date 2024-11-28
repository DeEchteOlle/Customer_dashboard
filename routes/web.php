<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PagespeedController;
use App\Models\PagespeedResult;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\DashboardController;


Route::get('/', [DashboardController::class, 'index']);
Route::get('/', function () {
    $results = PagespeedResult::with('website')->get(); // Ensure 'website' is loaded
    return view('pagespeed.results', compact('results'));
});

Route::get('websites/{id}/results', [PagespeedController::class, 'viewResults'])->name('websites.results');

Route::get('websites', [WebsiteController::class, 'index'])->name('websites.index');
Route::get('websites/create', [WebsiteController::class, 'create']);
Route::post('websites', [WebsiteController::class, 'store']);
Route::get('websites/{id}/edit', [WebsiteController::class, 'edit']);
Route::put('websites/{id}', [WebsiteController::class, 'update']);
Route::delete('websites/{id}', [WebsiteController::class, 'delete']);






