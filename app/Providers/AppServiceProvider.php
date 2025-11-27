<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
	    $this->publishes([
		    base_path('vendor/twbs/bootstrap') => public_path('assets/bootstrap'),
	    ], 'public');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
	    setlocale(LC_ALL, config('app.locale'));
    }
}
