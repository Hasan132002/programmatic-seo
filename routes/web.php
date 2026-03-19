<?php

use App\Http\Controllers\App\AnalyticsController;
use App\Http\Controllers\App\BillingController;
use App\Http\Controllers\App\BuilderController;
use App\Http\Controllers\App\ContentController;
use App\Http\Controllers\App\DashboardController;
use App\Http\Controllers\App\DataController;
use App\Http\Controllers\App\MonetizationController;
use App\Http\Controllers\App\PageController;
use App\Http\Controllers\App\SEOController;
use App\Http\Controllers\App\SiteController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\PlanController as AdminPlanController;
use App\Http\Controllers\Admin\SiteController as AdminSiteController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use Illuminate\Support\Facades\Route;

// Landing Page
Route::view('/', 'welcome');

// Profile
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// ─── User Dashboard ──────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'tenant'])
    ->prefix('app')
    ->name('app.')
    ->group(function () {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');

        // Sites
        Route::get('/sites', [SiteController::class, 'index'])->name('sites.index');
        Route::get('/sites/create', [SiteController::class, 'create'])->name('sites.create');
        Route::get('/sites/{site}', [SiteController::class, 'show'])->name('sites.show');
        Route::get('/sites/{site}/edit', [SiteController::class, 'edit'])->name('sites.edit');

        // Pages (nested under sites)
        Route::get('/sites/{site}/pages', [PageController::class, 'index'])->name('sites.pages.index');
        Route::get('/sites/{site}/pages/create', [PageController::class, 'create'])->name('sites.pages.create');
        Route::get('/sites/{site}/pages/{page}/edit', [PageController::class, 'edit'])->name('sites.pages.edit');

        // Visual Page Builder (GrapesJS)
        Route::get('/sites/{site}/builder', [BuilderController::class, 'create'])->name('sites.builder.create');
        Route::get('/sites/{site}/builder/{page}', [BuilderController::class, 'edit'])->name('sites.builder.edit');

        // Data Sources (nested under sites)
        Route::get('/sites/{site}/data', [DataController::class, 'index'])->name('sites.data.index');
        Route::get('/sites/{site}/data/import', [DataController::class, 'import'])->name('sites.data.import');
        Route::get('/sites/{site}/data/{dataSource}', [DataController::class, 'browse'])->name('sites.data.browse');

        // Content Generation (nested under sites)
        Route::get('/sites/{site}/content/generate', [ContentController::class, 'generate'])->name('sites.content.generate');
        Route::get('/sites/{site}/content/keywords', [ContentController::class, 'keywords'])->name('sites.content.keywords');
        Route::get('/sites/{site}/content/bulk', [ContentController::class, 'bulk'])->name('sites.content.bulk');
        Route::get('/sites/{site}/content/prompts', [ContentController::class, 'prompts'])->name('sites.content.prompts');

        // SEO Management (nested under sites)
        Route::get('/sites/{site}/seo', [SEOController::class, 'settings'])->name('sites.seo.settings');
        Route::get('/sites/{site}/seo/links', [SEOController::class, 'internalLinks'])->name('sites.seo.links');
        Route::get('/sites/{site}/seo/redirects', [SEOController::class, 'redirects'])->name('sites.seo.redirects');

        // Monetization (nested under sites)
        Route::get('/sites/{site}/monetization/ads', [MonetizationController::class, 'ads'])->name('sites.monetization.ads');
        Route::get('/sites/{site}/monetization/affiliates', [MonetizationController::class, 'affiliates'])->name('sites.monetization.affiliates');

        // Analytics (nested under sites)
        Route::get('/sites/{site}/analytics', [AnalyticsController::class, 'index'])->name('sites.analytics');

        // Billing & Subscription
        Route::get('/billing', [BillingController::class, 'index'])->name('billing');
        Route::get('/billing/checkout/{plan}', [BillingController::class, 'checkout'])->name('billing.checkout');
        Route::get('/billing/success', [BillingController::class, 'success'])->name('billing.success');
        Route::get('/billing/cancel', [BillingController::class, 'cancel'])->name('billing.cancel');
    });

// Redirect /dashboard to /app/dashboard
Route::get('dashboard', fn () => redirect()->route('app.dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// ─── Admin Panel ─────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/plans', [AdminPlanController::class, 'index'])->name('plans.index');
        Route::get('/sites', [AdminSiteController::class, 'index'])->name('sites.index');
        Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings.index');
    });

require __DIR__.'/auth.php';
