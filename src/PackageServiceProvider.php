<?php

declare(strict_types=1);

namespace Igne\Nova;

use Illuminate\Support\ServiceProvider;

/**
 * The package's own config and translations, and their publishing.
 */
final class PackageServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom($this->path('config/igne-nova.php'), 'igne-nova');
    }

    public function boot(): void
    {
        $this->loadTranslationsFrom($this->path('lang'), 'igne-nova');

        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            $this->path('config/igne-nova.php') => config_path('igne-nova.php'),
        ], 'igne-nova-config');

        $this->publishes([
            $this->path('lang') => $this->app->langPath('vendor/igne-nova'),
        ], 'igne-nova-lang');
    }

    private function path(string $path): string
    {
        return \dirname(__DIR__).'/'.$path;
    }
}
