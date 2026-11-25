<?php

declare(strict_types=1);

namespace Igne\Nova\Assets;

use Illuminate\Config\Repository as Config;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;
use Laravel\Nova\Nova;

/**
 * The app's own Nova stylesheets and scripts, and the lazy images script.
 */
final class AssetsServiceProvider extends ServiceProvider
{
    public function boot(Config $config): void
    {
        /** @var array<string, string> $styles */
        $styles = $config->array('igne-nova.assets.styles', []);
        /** @var array<string, string> $scripts */
        $scripts = $config->array('igne-nova.assets.scripts', []);

        if ($config->boolean('igne-nova.assets.lazy_images')) {
            $scripts = ['igne-nova-lazy-images' => \dirname(__DIR__, 2).'/resources/js/lazy-images.js', ...$scripts];
        }

        foreach ($styles as $name => $path) {
            Nova::style($name, $this->existing($path));
        }

        foreach ($scripts as $name => $path) {
            Nova::script($name, $this->existing($path));
        }
    }

    private function existing(string $path): string
    {
        if (! is_file($path)) {
            throw new InvalidArgumentException(\sprintf('The Nova asset [%s] in config igne-nova.assets does not exist.', $path));
        }

        return $path;
    }
}
