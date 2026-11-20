<?php

declare(strict_types=1);

namespace Igne\Nova;

use Illuminate\Support\ServiceProvider;

/**
 * Wires the package's Nova features into the app. Every feature is opt-in per
 * project through config/igne-nova.php, a trait or a class the app calls.
 */
final class IgneNovaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom($this->path('config/igne-nova.php'), 'igne-nova');
    }

    public function boot(): void
    {
        $this->configurePublishing();
    }

    private function configurePublishing(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            $this->path('config/igne-nova.php') => config_path('igne-nova.php'),
        ], 'igne-nova-config');
    }

    private function path(string $path): string
    {
        return \dirname(__DIR__).'/'.$path;
    }
}
