<?php

declare(strict_types=1);

use Igne\Nova\BadgeType;
use Igne\Nova\Menu\Exceptions\MenuGroupException;
use Igne\Nova\Menu\MenuGroupDiscovery;
use Igne\Nova\Tests\Fixtures\Menu\NovaGroup;
use Igne\Nova\Tests\Fixtures\OtherMenu\PortalGroup;

$fixtures = dirname(__DIR__).'/Fixtures';

it('finds the only menu group enum in the discovered folders', function (): void {
    expect(app(MenuGroupDiscovery::class)->enum())->toBe(NovaGroup::class);
});

it('uses the configured enum without discovering', function () use ($fixtures): void {
    config()->set('igne-nova.menu.groups', PortalGroup::class);
    config()->set('igne-nova.menu.discover', [$fixtures.'/Menu', $fixtures.'/OtherMenu']);

    expect(app(MenuGroupDiscovery::class)->enum())->toBe(PortalGroup::class);
});

it('refuses a configured class that is not a menu group enum', function (): void {
    config()->set('igne-nova.menu.groups', BadgeType::class);

    app(MenuGroupDiscovery::class)->enum();
})->throws(MenuGroupException::class, 'must be an enum that implements');

it('asks to configure the enum when the folders hold none', function () use ($fixtures): void {
    config()->set('igne-nova.menu.discover', [$fixtures.'/Nova']);

    app(MenuGroupDiscovery::class)->enum();
})->throws(MenuGroupException::class, 'No enum that implements');

it('asks to configure the enum when the folders hold several', function () use ($fixtures): void {
    config()->set('igne-nova.menu.discover', [$fixtures.'/Menu', $fixtures.'/OtherMenu']);

    app(MenuGroupDiscovery::class)->enum();
})->throws(MenuGroupException::class, 'Several enums implement');

it('refuses a discover folder that does not exist', function () use ($fixtures): void {
    config()->set('igne-nova.menu.discover', [$fixtures.'/Missing']);

    app(MenuGroupDiscovery::class)->enum();
})->throws(MenuGroupException::class, 'is not a directory');
