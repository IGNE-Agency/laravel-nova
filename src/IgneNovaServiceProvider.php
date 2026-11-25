<?php

declare(strict_types=1);

namespace Igne\Nova;

use Igne\Nova\Assets\AssetsServiceProvider;
use Igne\Nova\Branding\BrandingServiceProvider;
use Igne\Nova\Layout\LayoutServiceProvider;
use Igne\Nova\Localization\LocalizationServiceProvider;
use Igne\Nova\Menu\MenuServiceProvider;
use Igne\Nova\Routes\RoutesServiceProvider;
use Illuminate\Support\ServiceProvider;

/**
 * Builds the package from its providers: one for the package itself and one
 * per feature. Every feature is opt-in per project or can be turned off in
 * config/igne-nova.php, and the app's own Nova setup wins: its providers boot
 * after these, so its Nova::footer() and such replace the package's, and its
 * config/nova.php brand settings stay.
 */
final class IgneNovaServiceProvider extends ServiceProvider
{
    /**
     * In this order: the package's config is merged before the features read it.
     *
     * @var list<class-string<ServiceProvider>>
     */
    private const array PROVIDERS = [
        PackageServiceProvider::class,
        MenuServiceProvider::class,
        LayoutServiceProvider::class,
        LocalizationServiceProvider::class,
        BrandingServiceProvider::class,
        AssetsServiceProvider::class,
        RoutesServiceProvider::class,
    ];

    public function register(): void
    {
        foreach (self::PROVIDERS as $provider) {
            $this->app->register($provider);
        }
    }
}
