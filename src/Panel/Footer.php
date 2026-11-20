<?php

declare(strict_types=1);

namespace Igne\Nova\Panel;

use Illuminate\Support\Facades\Date;

/**
 * The panel's footer: the app's copyright and IGNE as its builder, instead of
 * Nova's version and copyright.
 */
final class Footer
{
    public function __construct(private readonly string $appName) {}

    public function render(): string
    {
        $copyright = __('igne-nova::panel.copyright', ['year' => Date::now()->year, 'app' => $this->appName]);
        $builtBy = __('igne-nova::panel.built_by', ['igne' => 'IGNE']);

        return \sprintf(
            '<p class="text-center">%s · <a class="link-default" href="https://igne.nl">%s</a></p>',
            e($copyright),
            e($builtBy),
        );
    }
}
