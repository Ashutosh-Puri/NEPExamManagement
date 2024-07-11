<?php

namespace App\Providers;

use App\Models\College;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppNameServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }

    public function boot(): void
    {   
        try {
            if (Schema::hasTable('colleges')) {
                $appName = cache()->remember('default_college_name', now()->addHours(24), function () {
                    return College::where('is_default', 1)->pluck('college_name')->first();
                });
    
                if ($appName) {
                    config(['app.name' => $appName]);
                } else {
                    config(['app.name' => env('APP_NAME')]);
                }
            } else {
                config(['app.name' => env('APP_NAME')]);
            }
        } catch (\Exception $e) {
            config(['app.name' => env('APP_NAME')]);
        }
    }
}
