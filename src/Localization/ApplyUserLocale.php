<?php

declare(strict_types=1);

namespace Igne\Nova\Localization;

use Illuminate\Contracts\Translation\HasLocalePreference;
use Laravel\Nova\Events\ServingNova;

/**
 * Serves Nova in the user's preferred locale. Nova's own serving listener
 * loads the translations of the locale at that moment, so this listener is
 * registered before it.
 */
final class ApplyUserLocale
{
    public function handle(ServingNova $event): void
    {
        $user = $event->request->user();

        if (! $user instanceof HasLocalePreference) {
            return;
        }

        $locale = $user->preferredLocale();

        if ($locale !== null && $locale !== '') {
            $event->app->setLocale($locale);
        }
    }
}
