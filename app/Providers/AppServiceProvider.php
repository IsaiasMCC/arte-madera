<?php

namespace App\Providers;

use App\Models\PageVisit;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer('*', function ($view) {
            $currentPage = request()->path();
            $pageVisit = PageVisit::where('page', $currentPage)->first();
            $visits = $pageVisit ? $pageVisit->visits : 0;

            $view->with('pageVisits', $visits);
        });
    }
}
