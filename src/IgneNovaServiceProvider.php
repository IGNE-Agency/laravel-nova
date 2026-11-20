<?php

declare(strict_types=1);

namespace Igne\Nova;

use Igne\Nova\Branding\BrandColors;
use Igne\Nova\Panel\ApplyUserLocale;
use Igne\Nova\Panel\Assets;
use Igne\Nova\Panel\Footer;
use Illuminate\Config\Repository as Config;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

/**
 * Wires the package's Nova features into the app. Every feature is opt-in per
 * project or can be turned off in config/igne-nova.php, and the app's own Nova
 * setup wins: its provider boots after this one, so its Nova::footer() and
 * such replace the package's, and its config/nova.php brand settings stay.
 */
final class IgneNovaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom($this->path('config/igne-nova.php'), 'igne-nova');

        // Registered before Nova's own serving listener, which Nova registers
        // in boot(): that one loads the translations of the locale set here.
        $this->app->make(Dispatcher::class)->listen(ServingNova::class, ApplyUserLocale::class);
    }

    public function boot(): void
    {
        $this->loadTranslationsFrom($this->path('lang'), 'igne-nova');

        $config = $this->app->make(Config::class);

        $this->configurePanel($config);
        $this->configureBrand($config);
        $this->configureAssets($config);
        $this->configurePublishing();
    }

    private function configurePanel(Config $config): void
    {
        Nova::withBreadcrumbs($config->boolean('igne-nova.breadcrumbs'));

        $initialPath = $config->get('igne-nova.initial_path');

        if (\is_string($initialPath) && $initialPath !== '') {
            Nova::initialPath($initialPath);
        }

        Nova::userTimezone(static fn (Request $request): string => IgneNova::timezoneFor(
            $request->user(),
            $config->string('app.timezone', 'UTC'),
        ));

        if ($config->boolean('igne-nova.footer')) {
            Nova::footer(static fn (): string => (new Footer($config->string('app.name')))->render());
        }
    }

    /**
     * Fill Nova's brand settings the app's config/nova.php leaves empty.
     */
    private function configureBrand(Config $config): void
    {
        $logo = $config->get('igne-nova.brand.logo');

        if (\is_string($logo) && $config->get('nova.brand.logo') === null) {
            $config->set('nova.brand.logo', $logo);
        }

        $color = $config->get('igne-nova.brand.color');

        if ((\is_string($color) || \is_array($color)) && $config->get('nova.brand.colors') === null) {
            /** @var string|array<int|string, string> $color */
            $config->set('nova.brand.colors', BrandColors::scale($color));
        }
    }

    private function configureAssets(Config $config): void
    {
        $assets = new Assets;

        /** @var array<string, string> $styles */
        $styles = $config->array('igne-nova.assets.styles', []);
        /** @var array<string, string> $scripts */
        $scripts = $config->array('igne-nova.assets.scripts', []);

        if ($config->boolean('igne-nova.lazy_images')) {
            $scripts = ['igne-nova-lazy-images' => $this->path('resources/js/lazy-images.js'), ...$scripts];
        }

        $assets->register($styles, $scripts);
    }

    private function configurePublishing(): void
    {
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
