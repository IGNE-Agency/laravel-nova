<?php

declare(strict_types=1);

namespace Igne\Nova\Layout;

use Illuminate\Config\Repository as Config;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Nova;

/**
 * Breadcrumbs, the path Nova opens after login, and the IGNE footer.
 */
final class LayoutServiceProvider extends ServiceProvider
{
    public function boot(Config $config): void
    {
        Nova::withBreadcrumbs($config->boolean('igne-nova.layout.breadcrumbs'));

        $initialPath = $config->get('igne-nova.layout.initial_path');

        if (\is_string($initialPath) && $initialPath !== '') {
            Nova::initialPath($initialPath);
        }

        if ($config->boolean('igne-nova.layout.footer')) {
            Nova::footer(static fn (): string => (new Footer($config->string('app.name')))->render());
        }
    }
}
