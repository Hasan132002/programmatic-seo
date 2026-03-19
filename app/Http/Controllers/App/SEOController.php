<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Site;

class SEOController extends Controller
{
    public function settings(Site $site)
    {
        return view('app.seo.settings', compact('site'));
    }

    public function internalLinks(Site $site)
    {
        return view('app.seo.internal-links', compact('site'));
    }

    public function redirects(Site $site)
    {
        return view('app.seo.redirects', compact('site'));
    }
}
