<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\DataSource;
use App\Models\Site;

class DataController extends Controller
{
    public function index(Site $site)
    {
        return view('app.data.index', compact('site'));
    }

    public function import(Site $site)
    {
        return view('app.data.import', compact('site'));
    }

    public function browse(Site $site, DataSource $dataSource)
    {
        // Ensure the data source belongs to this site
        abort_unless($dataSource->site_id === $site->id, 404);

        return view('app.data.browse', compact('site', 'dataSource'));
    }
}
