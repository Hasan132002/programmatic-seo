<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Site;

class ContentController extends Controller
{
    /**
     * Show the single-page AI content generation form.
     */
    public function generate(Site $site)
    {
        return view('app.content.generate', compact('site'));
    }

    /**
     * Show the bulk page generation wizard.
     */
    public function bulk(Site $site)
    {
        return view('app.content.bulk', compact('site'));
    }

    /**
     * Show the keyword-to-pages generator.
     */
    public function keywords(Site $site)
    {
        return view('app.content.keywords', compact('site'));
    }

    /**
     * Show the prompt template management page.
     */
    public function prompts(Site $site)
    {
        return view('app.content.prompts', compact('site'));
    }
}
