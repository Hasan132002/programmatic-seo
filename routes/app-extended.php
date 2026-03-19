<?php

use App\Http\Controllers\App\SEOController;
use App\Http\Controllers\App\MonetizationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Extended App Routes (SEO & Monetization)
|--------------------------------------------------------------------------
|
| These routes should be included inside the authenticated app route group
| in routes/web.php, or loaded separately with the same middleware:
|
| Route::middleware(['auth', 'verified', 'tenant'])
|     ->prefix('app')
|     ->name('app.')
|     ->group(base_path('routes/app-extended.php'));
|
*/

// ─── SEO Management ─────────────────────────────────────────────
Route::get('/sites/{site}/seo', [SEOController::class, 'settings'])->name('sites.seo.settings');
Route::get('/sites/{site}/seo/links', [SEOController::class, 'internalLinks'])->name('sites.seo.links');
Route::get('/sites/{site}/seo/redirects', [SEOController::class, 'redirects'])->name('sites.seo.redirects');

// ─── Monetization ───────────────────────────────────────────────
Route::get('/sites/{site}/monetization/ads', [MonetizationController::class, 'ads'])->name('sites.monetization.ads');
Route::get('/sites/{site}/monetization/affiliates', [MonetizationController::class, 'affiliates'])->name('sites.monetization.affiliates');
