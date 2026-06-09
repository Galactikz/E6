<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\ProviderCategory;
use App\Models\SubscriptionPlan;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $plans = SubscriptionPlan::where('is_active', true)->orderBy('sort_order')->get();
        $categories = ProviderCategory::orderBy('sort_order')->limit(6)->get();

        return view('home', compact('plans', 'categories'));
    }
}
