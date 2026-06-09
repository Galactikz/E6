<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\ProviderCategory;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $categories = ProviderCategory::all();
        $providers = Provider::active()->with('category')->get();

        $xml = view('sitemap', compact('categories', 'providers'));

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
