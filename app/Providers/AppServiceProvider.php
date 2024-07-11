<?php

namespace App\Providers;

use App\Models\Cgparesult;
use App\Models\Studentmark;
use App\Models\Academicyear;
use App\Models\Studentresult;
use App\Observers\DBChangeObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;

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
        Cgparesult::observe(DBChangeObserver::class);
        Studentmark::observe(DBChangeObserver::class);
        Studentresult::observe(DBChangeObserver::class);
    }
}
