<?php

declare(strict_types=1);

namespace Igne\Nova\Tests\Fixtures\Nova;

use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Dashboards\Main;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

/**
 * The Nova setup of an app that runs the package: Nova's own login routes,
 * the main dashboard and the fixture resources. Every user may open Nova.
 */
final class NovaServiceProvider extends NovaApplicationServiceProvider
{
    protected function routes(): void
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->withoutEmailVerificationRoutes()
            ->register();
    }

    protected function gate(): void
    {
        Gate::define('viewNova', static fn (): bool => true);
    }

    /**
     * @return list<Main>
     */
    protected function dashboards(): array
    {
        return [new Main];
    }

    protected function resources(): void
    {
        Nova::resources([User::class]);
    }
}
