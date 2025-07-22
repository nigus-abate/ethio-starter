<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
        Paginator::defaultView('vendor.pagination.bootstrap-5');
        // Cache settings for 24 hours
        try {
            if (
                config('database.default') !== 'sqlite' ||
                file_exists(database_path('database.sqlite'))
            ) {
                if (Schema::hasTable('settings')) {
                    Cache::remember('settings', 60 * 24, function () {
                        return Setting::all()->keyBy('key');
                    });
                }
            }
        } catch (\Exception $e) {
            // Swallow or log exception to avoid CI fail
        }
    }
}
