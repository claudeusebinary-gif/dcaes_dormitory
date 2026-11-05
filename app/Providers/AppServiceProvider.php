<?php

namespace App\Providers;
use App\Support\DormStats;
use Illuminate\Support\Facades\View;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
   
    public function register(): void
    {
        //
    }

    public function boot(): void
{
    View::composer(['layouts.app', 'partials.sidebar', 'partials.footer'], function ($view) {
        $view->with('dorm', DormStats::profile());
    });
}
}
