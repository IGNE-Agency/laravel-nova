<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Laravel\Nova\Nova;

it('shows the app\'s copyright and IGNE as its builder in the footer', function (): void {
    config()->set('app.name', 'Acme & Co');
    app()->setLocale('nl');

    $footer = Nova::resolveFooter(Request::create('/nova'));

    expect($footer)
        ->toContain(trans('igne-nova::panel.copyright', ['year' => now()->year, 'app' => e('Acme & Co')]))
        ->toContain(trans('igne-nova::panel.built_by', ['igne' => 'IGNE']))
        ->toContain('href="https://igne.nl"');
});

it('keeps Nova\'s footer when the package footer is off', function (): void {
    Nova::$footerCallback = null;
    config()->set('igne-nova.footer', false);

    $this->rebootPackage();

    expect(Nova::resolveFooter(Request::create('/nova')))->toBe(Nova::defaultFooter(Request::create('/nova')));
});

it('turns breadcrumbs on by default and off on request', function (): void {
    expect(Nova::breadcrumbsEnabled())->toBeTrue();

    config()->set('igne-nova.breadcrumbs', false);
    $this->rebootPackage();

    expect(Nova::breadcrumbsEnabled())->toBeFalse();
});

it('opens the configured initial path', function (): void {
    config()->set('igne-nova.initial_path', '/resources/users');

    $this->rebootPackage();

    expect(Nova::resolveInitialPath(Request::create('/nova')))->toBe('/resources/users');
});
