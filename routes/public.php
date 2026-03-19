<?php

use App\Http\Controllers\PublicSite\SitePageController;
use Illuminate\Support\Facades\Route;

// Public site routes - these are accessed via custom domains/subdomains
Route::middleware(['resolve-site'])
    ->group(function () {
        Route::get('/sitemap.xml', [SitePageController::class, 'sitemap']);
        Route::get('/robots.txt', [SitePageController::class, 'robots']);
        Route::get('/{slug?}', [SitePageController::class, 'show'])->where('slug', '.*')->middleware('track-pageview');
    });
