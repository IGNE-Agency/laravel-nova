<?php

declare(strict_types=1);

use Igne\Nova\Assets\AssetsServiceProvider;
use Laravel\Nova\Nova;

/**
 * @return list<string>
 */
function scriptNames(): array
{
    return array_map(static fn ($script): string => $script->name(), Nova::$scripts);
}

it('registers the lazy images script by default', function (): void {
    expect(scriptNames())->toContain('igne-nova-lazy-images');
});

it('leaves the lazy images script out when it is off', function (): void {
    Nova::$scripts = [];
    config()->set('igne-nova.assets.lazy_images', false);

    $this->rebootProvider(AssetsServiceProvider::class);

    expect(scriptNames())->not->toContain('igne-nova-lazy-images');
});

it('registers the app\'s stylesheets and scripts', function (): void {
    config()->set('igne-nova.assets.styles', ['brand' => __FILE__]);
    config()->set('igne-nova.assets.scripts', ['tweaks' => __FILE__]);

    $this->rebootProvider(AssetsServiceProvider::class);

    expect(scriptNames())->toContain('tweaks')
        ->and(array_map(static fn ($style): string => $style->name(), Nova::$styles))->toContain('brand');
});

it('fails on an asset that does not exist', function (): void {
    config()->set('igne-nova.assets.styles', ['brand' => '/does/not/exist.css']);

    $this->rebootProvider(AssetsServiceProvider::class);
})->throws(InvalidArgumentException::class);
