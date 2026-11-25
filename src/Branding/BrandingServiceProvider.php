<?php

declare(strict_types=1);

namespace Igne\Nova\Branding;

use Illuminate\Config\Repository as Config;
use Illuminate\Support\ServiceProvider;

/**
 * Fills Nova's brand settings the app's config/nova.php leaves empty.
 */
final class BrandingServiceProvider extends ServiceProvider
{
    public function boot(Config $config): void
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
}
