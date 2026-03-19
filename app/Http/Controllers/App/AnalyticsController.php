<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Site;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index(Site $site)
    {
        return view('app.analytics.index', compact('site'));
    }
}
