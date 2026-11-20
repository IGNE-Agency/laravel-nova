<?php

declare(strict_types=1);

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
    config()->set('igne-nova.lazy_images', false);

    $this->rebootPackage();

    expect(scriptNames())->not->toContain('igne-nova-lazy-images');
});

it('registers the app\'s stylesheets and scripts', function (): void {
    config()->set('igne-nova.assets', [
        'styles' => ['brand' => __FILE__],
        'scripts' => ['tweaks' => __FILE__],
    ]);

    $this->rebootPackage();

    expect(scriptNames())->toContain('tweaks')
        ->and(array_map(static fn ($style): string => $style->name(), Nova::$styles))->toContain('brand');
});

it('fails on an asset that does not exist', function (): void {
    config()->set('igne-nova.assets.styles', ['brand' => '/does/not/exist.css']);

    $this->rebootPackage();
})->throws(InvalidArgumentException::class);
