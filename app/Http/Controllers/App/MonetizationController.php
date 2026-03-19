<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Site;

class MonetizationController extends Controller
{
    public function ads(Site $site)
    {
        return view('app.monetization.ads', compact('site'));
    }

    public function affiliates(Site $site)
    {
        return view('app.monetization.affiliates', compact('site'));
    }
}
