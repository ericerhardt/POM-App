<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use E3DevelopmentSolutions\QuickBooks\Services\QuickBooksBaseService;

class QuickBooksServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish configuration
        $this->publishes([
            __DIR__ . '/config/quickbooks.php' => config_path('quickbooks.php'),
        ], 'config');

        // Publish migrations
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'migrations');

        // Register routes - make sure the file exists
        if (file_exists(__DIR__ . '/../routes/web.php')) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        }

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                // Register commands here if you have any
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Merge configuration
        $this->mergeConfigFrom(
             config_path('quickbooks.php'), 'quickbooks'
        );

        // Register services
        $this->app->singleton(QuickBooksBaseService::class, function ($app) {
            return new QuickBooksBaseService();
        });

        // Register facade
        $this->app->bind('quickbooks', function ($app) {
            return $app->make(QuickBooksBaseService::class);
        });
    }
}
