<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Site;

class BuilderController extends Controller
{
    /**
     * Show the visual page builder for creating a new page.
     */
    public function create(Site $site)
    {
        if (! auth()->user()->canCreatePage($site)) {
            return redirect()->route('app.sites.pages.index', $site)
                ->with('error', 'Page limit reached. Please upgrade your plan.');
        }

        return view('app.builder.editor', compact('site'));
    }

    /**
     * Show the visual page builder for editing an existing page.
     */
    public function edit(Site $site, Page $page)
    {
        return view('app.builder.editor', compact('site', 'page'));
    }
}
