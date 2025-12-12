<?php

namespace YourName\ItalicFonts\Providers;

use Illuminate\Support\ServiceProvider;
use YourName\ItalicFonts\Middleware\ItalicizeFonts;
use Illuminate\Support\Facades\View;
class ItalicFontsServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Merge package configuration
        $this->mergeConfigFrom(
            __DIR__.'/../../config/italic-fonts.php', 'italic-fonts'
        );
        
        // Register the main package class
        $this->app->singleton('italic-fonts', function ($app) {
            return new \YourName\ItalicFonts\ItalicFonts($app['config']['italic-fonts']);
        });
    }

    public function boot()
    {
        // Publish configuration
        $this->publishes([
            __DIR__.'/../../config/italic-fonts.php' => config_path('italic-fonts.php'),
        ], 'config');

        // Publish assets if any
        $this->publishes([
            __DIR__.'/../../resources/css' => public_path('vendor/italic-fonts'),
        ], 'assets');

        // Register the middleware globally
        $this->app['router']->pushMiddlewareToGroup('web', ItalicizeFonts::class);
        $this->app['router']->pushMiddlewareToGroup('api', ItalicizeFonts::class);

        // Register console command
        if ($this->app->runningInConsole()) {
            $this->commands([
                \YourName\ItalicFonts\Console\Commands\InstallItalicFontsCommand::class,
            ]);
        }

        // Add view composer to apply italics to all views
        $this->addItalicViewComposer();
    }

    protected function addItalicViewComposer()
    {
        View::composer('*', function ($view) {
            // Share a CSS class that makes text italic
            $view->with('italicClass', 'italic-fonts-style');
        });
    }
}