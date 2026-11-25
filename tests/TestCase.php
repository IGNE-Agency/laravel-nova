<?php

declare(strict_types=1);

namespace Igne\Nova\Tests;

use Closure;
use Igne\Nova\IgneNovaServiceProvider;
use Igne\Nova\Menu\GroupedMenu;
use Igne\Nova\Tests\Fixtures\Nova\NovaServiceProvider;
use Igne\Nova\Tests\Fixtures\User;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Inertia\ServiceProvider as InertiaServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaCoreServiceProvider;
use Laravel\Nova\Testing\Concerns\InteractsWithNova;
use Orchestra\Testbench\TestCase as Orchestra;
use ReflectionClass;

/**
 * Runs the package on a bare Testbench app with Nova and a fixture Nova setup,
 * in the provider order of a real app: the package registers first.
 */
abstract class TestCase extends Orchestra
{
    use InteractsWithNova;

    protected function setUp(): void
    {
        parent::setUp();

        // Nova's assets are not built in the package, so the layout renders without them.
        $this->withoutMix();
    }

    /**
     * Nova keeps its registrations in static properties, which outlive the
     * test's app. Only Octane flushes them in a real app.
     */
    protected function tearDown(): void
    {
        Nova::flushState();

        parent::tearDown();
    }

    /**
     * @param  Application  $app
     * @return list<class-string<ServiceProvider>>
     */
    protected function getPackageProviders($app): array
    {
        return [
            IgneNovaServiceProvider::class,
            InertiaServiceProvider::class,
            NovaCoreServiceProvider::class,
            NovaServiceProvider::class,
        ];
    }

    /**
     * @param  Application  $app
     */
    protected function defineEnvironment($app): void
    {
        tap($app->make(Repository::class), static function (Repository $config): void {
            $config->set('app.key', 'base64:'.base64_encode(str_repeat('k', 32)));
            $config->set('app.locale', 'en');
            $config->set('app.fallback_locale', 'en');
            $config->set('app.timezone', 'UTC');
            $config->set('auth.providers.users.model', User::class);
            $config->set('cache.default', 'array');
            $config->set('database.default', 'testing');
            $config->set('database.connections.testing', [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
                'foreign_key_constraints' => true,
            ]);
            $config->set('hashing.bcrypt.rounds', 4);
            $config->set('igne-nova.menu.discover', [__DIR__.'/Fixtures/Menu']);
            $config->set('session.driver', 'array');
        });
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadLaravelMigrations();
        $this->loadMigrationsFrom(\dirname((string) (new ReflectionClass(Nova::class))->getFileName(), 2).'/database/migrations');
    }

    /**
     * Serve Nova to the user and build the menu as Nova serializes it for the page.
     *
     * @param  Closure(NovaRequest): GroupedMenu  $build
     * @return list<array<string, mixed>>
     */
    protected function serializedMenu(Closure $build, ?User $user = null): array
    {
        $user ??= User::factory()->create();
        $request = NovaRequest::create('/nova');
        $request->setUserResolver(static fn (): User => $user);
        $this->app->instance(NovaRequest::class, $request);
        $this->actingAs($user);
        event(new ServingNova($this->app, $request));

        /** @var list<array<string, mixed>> */
        return json_decode((string) json_encode($build($request)->sections()), true);
    }
}
