<?php

declare(strict_types=1);

namespace Igne\Nova\Routes;

use Illuminate\Config\Repository as Config;
use Illuminate\Support\ServiceProvider;

/**
 * The app's own routes on Nova's path, from routes/admin.php. Nova's own
 * routes stay in the app's NovaServiceProvider: a routes file is skipped once
 * routes are cached, and Nova::routes()->register() also turns on its login.
 */
final class RoutesServiceProvider extends ServiceProvider
{
    public function boot(Config $config): void
    {
        $this->publishes([
            \dirname(__DIR__, 2).'/stubs/admin.php' => $this->app->basePath('routes/admin.php'),
        ], 'igne-nova-routes');

        $file = $config->get('igne-nova.routes.file');

        if (! \is_string($file) || ! is_file($this->app->basePath($file))) {
            return;
        }

        $this->loadRoutesFrom($this->app->basePath($file));
    }
}
