<?php

declare(strict_types=1);

namespace Igne\Nova\Localization;

use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

/**
 * Gives Nova's screens the package's translations, such as Dutch, for the
 * locale Nova is served in. The app's own lang/vendor/nova/{locale}.json is
 * merged again after them, so the app's translations always win, whichever
 * serving listener runs first.
 */
final class LoadNovaTranslations
{
    public function __construct(private readonly string $path) {}

    public function handle(ServingNova $event): void
    {
        $locale = $event->app->getLocale();

        Nova::translations($this->path.'/'.$locale.'.json');
        Nova::translations($event->app->langPath('vendor/nova/'.$locale.'.json'));
    }
}
