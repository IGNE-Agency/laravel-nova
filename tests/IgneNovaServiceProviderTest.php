<?php

declare(strict_types=1);

use Igne\Nova\Assets\AssetsServiceProvider;
use Igne\Nova\Branding\BrandingServiceProvider;
use Igne\Nova\Layout\LayoutServiceProvider;
use Igne\Nova\Localization\LocalizationServiceProvider;
use Igne\Nova\Menu\MenuServiceProvider;
use Igne\Nova\PackageServiceProvider;
use Igne\Nova\Routes\RoutesServiceProvider;
use Igne\Nova\Tests\Fixtures\User;

it('registers the package\'s providers', function (string $provider): void {
    expect(app()->getProvider($provider))->toBeInstanceOf($provider);
})->with([
    PackageServiceProvider::class,
    MenuServiceProvider::class,
    LayoutServiceProvider::class,
    LocalizationServiceProvider::class,
    BrandingServiceProvider::class,
    AssetsServiceProvider::class,
    RoutesServiceProvider::class,
]);

it('merges the package config', function (): void {
    expect(config('igne-nova'))->toBeArray();
});

it('serves Nova with the package installed', function (): void {
    $this->actingAs(User::factory()->create())
        ->get('/nova/dashboards/main')
        ->assertOk();
});
