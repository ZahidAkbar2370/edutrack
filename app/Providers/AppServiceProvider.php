<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
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
        Paginator::useBootstrapFive();

        View::composer(['frontend.layout', 'frontend.pages.*', 'auth.*', 'schooladmin.dashboard.*'], function ($view) {
            $view->with([
                'supportEmail' => 'edutrack.softwebies@gmail.com',
                'supportPhone' => '+92 308 1312527',
                'supportWhatsApp' => '923200470584',
                'supportAddress' => 'Housing Colony 2, B Block, Layyah, Pakistan',
            ]);
        });
    }
}
