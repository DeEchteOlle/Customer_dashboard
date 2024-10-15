<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagespeedController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\DashboardController;



Route::get('/', [DashboardController::class, 'index']);

Route::get('/pagespeed', [PagespeedController::class, 'index']);

Route::get('websites', [WebsiteController::class, 'index']);
Route::get('websites/create', [WebsiteController::class, 'create']);
Route::post('websites', [WebsiteController::class, 'store']);
Route::get('websites/{id}/edit', [WebsiteController::class, 'edit']);
Route::put('websites/{id}', [WebsiteController::class, 'update']);
Route::delete('websites/{id}', [WebsiteController::class, 'delete']);





