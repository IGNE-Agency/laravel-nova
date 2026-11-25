<?php

declare(strict_types=1);

namespace Igne\Nova\Localization;

use Igne\Nova\IgneNova;
use Illuminate\Config\Repository as Config;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

/**
 * Serves Nova in the user's preferred locale and timezone.
 */
final class LocalizationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Registered before Nova's own serving listener, which Nova registers
        // in boot(): that one loads the translations of the locale set here.
        $this->app->make(Dispatcher::class)->listen(ServingNova::class, ApplyUserLocale::class);
    }

    public function boot(Config $config): void
    {
        Nova::userTimezone(static fn (Request $request): string => IgneNova::timezoneFor(
            $request->user(),
            $config->string('app.timezone', 'UTC'),
        ));
    }
}
