<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Pagination\PaginationService;
use App\Services\FilterAndSort\FilterAndSortService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('FilterAndSortService', function() {
            return new FilterAndSortService();
        }); 

        $this->app->bind('PaginationService', function() {
            return new PaginationService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
