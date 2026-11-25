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
 * Serves Nova in the user's preferred locale and timezone, with the package's
 * translations of Nova's texts, such as Dutch.
 */
final class LocalizationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Registered before Nova's own serving listener, which Nova registers
        // in boot(): that one loads the translations of the locale set here.
        $events = $this->app->make(Dispatcher::class);
        $events->listen(ServingNova::class, ApplyUserLocale::class);
        $events->listen(ServingNova::class, function (ServingNova $event): void {
            (new LoadNovaTranslations($this->novaLangPath()))->handle($event);
        });
    }

    public function boot(Config $config): void
    {
        // Nova's server-side texts, such as action and validation messages.
        $this->loadJsonTranslationsFrom($this->novaLangPath());

        Nova::userTimezone(static fn (Request $request): string => IgneNova::timezoneFor(
            $request->user(),
            $config->string('app.timezone', 'UTC'),
        ));
    }

    private function novaLangPath(): string
    {
        return \dirname(__DIR__, 2).'/lang/nova';
    }
}
