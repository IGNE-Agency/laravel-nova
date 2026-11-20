<?php

declare(strict_types=1);

use Igne\Nova\Branding\BrandColors;

it('makes Nova\'s full primary scale from one hex colour, with the colour as shade 500', function (): void {
    $scale = BrandColors::scale('#18b69b');

    expect(array_keys($scale))->toBe([50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950])
        ->and($scale['500'])->toBe('24, 182, 155');
});

it('mixes the lighter shades toward white and the darker ones toward black', function (): void {
    $scale = BrandColors::scale('808080');

    expect($scale['50'])->toBe('249, 249, 249')
        ->and($scale['950'])->toBe('32, 32, 32');
});

it('converts a full scale as given', function (): void {
    expect(BrandColors::scale([50 => '#ffffff', 500 => '#000000']))->toBe([50 => '255, 255, 255', 500 => '0, 0, 0']);
});

it('rejects a colour that is not a six-digit hex colour', function (): void {
    BrandColors::scale('teal');
})->throws(InvalidArgumentException::class);

it('fills Nova\'s brand settings from the package config', function (): void {
    config()->set('igne-nova.brand', ['logo' => '/path/logo.svg', 'color' => '#18b69b']);

    $this->rebootPackage();

    expect(config('nova.brand.logo'))->toBe('/path/logo.svg')
        ->and(config('nova.brand.colors.500'))->toBe('24, 182, 155');
});

it('keeps the brand settings of the app\'s Nova config', function (): void {
    config()->set('nova.brand', ['logo' => '/app/logo.svg', 'colors' => ['500' => '1, 2, 3']]);
    config()->set('igne-nova.brand', ['logo' => '/path/logo.svg', 'color' => '#18b69b']);

    $this->rebootPackage();

    expect(config('nova.brand'))->toBe(['logo' => '/app/logo.svg', 'colors' => ['500' => '1, 2, 3']]);
});
