<?php

declare(strict_types=1);

namespace Igne\Nova\Menu;

use Illuminate\Support\ServiceProvider;

/**
 * The grouped main menu. The app builds it from Nova::mainMenu(); see docs/menu.md.
 */
final class MenuServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(MenuGroupDiscovery::class);
    }
}
