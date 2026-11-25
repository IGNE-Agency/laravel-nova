<?php

declare(strict_types=1);

use Igne\Nova\Routes\RoutesServiceProvider;
use Illuminate\Support\Facades\File;

beforeEach(function (): void {
    File::ensureDirectoryExists(base_path('routes'));
    File::put(base_path('routes/admin.php'), <<<'PHP'
        <?php

        use Laravel\Nova\Nova;

        Nova::router()->get('/ping', static fn (): string => 'pong')->name('nova.ping');
        PHP);
});

afterEach(function (): void {
    File::delete(base_path('routes/admin.php'));
});

it('serves the app\'s routes from routes/admin.php on Nova\'s path', function (): void {
    $this->rebootProvider(RoutesServiceProvider::class);

    $this->get('/nova/ping')
        ->assertOk()
        ->assertSee('pong');
});

it('loads another file when configured', function (): void {
    File::move(base_path('routes/admin.php'), base_path('routes/nova.php'));
    config()->set('igne-nova.routes.file', 'routes/nova.php');

    $this->rebootProvider(RoutesServiceProvider::class);

    File::delete(base_path('routes/nova.php'));

    $this->get('/nova/ping')->assertOk();
});

it('loads no routes when turned off', function (): void {
    config()->set('igne-nova.routes.file', null);

    $this->rebootProvider(RoutesServiceProvider::class);

    $this->get('/nova/ping')->assertNotFound();
});
