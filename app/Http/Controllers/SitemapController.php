<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class SitemapController extends Controller
{
    public function index()
    {
        $urls = [
            [
                'loc' => route('home'),
                'lastmod' => Carbon::now()->toW3cString(),
                'changefreq' => 'weekly',
                'priority' => '1.0',
            ],
        ];

        return response()->view('sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml');
    }
}
