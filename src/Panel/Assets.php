<?php

declare(strict_types=1);

namespace Igne\Nova\Panel;

use InvalidArgumentException;
use Laravel\Nova\Nova;

/**
 * Registers the app's own Nova stylesheets and scripts from the config.
 */
final class Assets
{
    /**
     * @param  array<string, string>  $styles  name => absolute path
     * @param  array<string, string>  $scripts  name => absolute path
     */
    public function register(array $styles, array $scripts): void
    {
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
