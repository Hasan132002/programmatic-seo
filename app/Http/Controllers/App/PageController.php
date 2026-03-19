<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Site;

class PageController extends Controller
{
    public function index(Site $site)
    {
        return view('app.pages.index', compact('site'));
    }

    public function create(Site $site)
    {
        if (!auth()->user()->canCreatePage($site)) {
            return redirect()->route('app.sites.pages.index', $site)
                ->with('error', 'Page limit reached. Please upgrade your plan.');
        }

        return view('app.pages.create', compact('site'));
    }

    public function edit(Site $site, Page $page)
    {
        return view('app.pages.edit', compact('site', 'page'));
    }
}
