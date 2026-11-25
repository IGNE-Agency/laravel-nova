<?php

declare(strict_types=1);

use Igne\Nova\Menu\Exceptions\MenuGroupException;
use Igne\Nova\Menu\GroupedMenu;
use Igne\Nova\Tests\Fixtures\OtherMenu\PortalGroup;
use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuSection;

it('lists the dashboards first, then a section per group in the order the enum declares its cases', function (): void {
    $menu = $this->serializedMenu(static fn (Request $request): GroupedMenu => GroupedMenu::make($request));

    expect(array_column($menu, 'name'))->toBe(['Main', 'Content', 'Users', 'Resources', 'Settings']);
});

it('lists a resource without a known group in the fallback group', function (): void {
    $menu = $this->serializedMenu(static fn (Request $request): GroupedMenu => GroupedMenu::make($request));

    $resources = collect($menu)->firstWhere('name', 'Resources');

    expect(array_column($resources['items'], 'name'))->toBe(['Notes']);
});

it('lists the resources of a group in Nova\'s order, from menuGroup() and from Nova\'s string group alike', function (): void {
    $menu = $this->serializedMenu(static fn (Request $request): GroupedMenu => GroupedMenu::make($request));

    $users = collect($menu)->firstWhere('name', 'Users');

    expect(array_column($users['items'], 'name'))->toBe(['Invitations', 'Users'])
        ->and($users['icon'])->toBe('users');
});

it('makes the sections collapsable and closes the groups that ask for it', function (): void {
    $menu = collect($this->serializedMenu(static fn (Request $request): GroupedMenu => GroupedMenu::make($request)));

    expect($menu->firstWhere('name', 'Users'))->toMatchArray(['collapsable' => true, 'collapsedByDefault' => false])
        ->and($menu->firstWhere('name', 'Settings'))->toMatchArray(['collapsable' => true, 'collapsedByDefault' => true]);
});

it('leaves the dashboards out on request', function (): void {
    $menu = $this->serializedMenu(static fn (Request $request): GroupedMenu => GroupedMenu::make($request)->withoutDashboards());

    expect(array_column($menu, 'name'))->not->toContain('Main');
});

it('adds the prepended sections after the dashboards and the appended ones after the groups', function (): void {
    $menu = $this->serializedMenu(static fn (Request $request): GroupedMenu => GroupedMenu::make($request)
        ->prepend(MenuSection::make('First'))
        ->prependWhen(false, MenuSection::make('Skipped'))
        ->append(MenuSection::make('Last'), MenuSection::make('Very last'))
        ->appendWhen(true, MenuSection::make('Conditional')));

    expect(array_column($menu, 'name'))->toBe(['Main', 'First', 'Content', 'Users', 'Resources', 'Settings', 'Last', 'Very last', 'Conditional']);
});

it('refuses a resource whose menuGroup() is a case of another enum than the menu uses', function (): void {
    config()->set('igne-nova.menu.groups', PortalGroup::class);

    $this->serializedMenu(static fn (Request $request): GroupedMenu => GroupedMenu::make($request));
})->throws(MenuGroupException::class, 'Article::menuGroup() returns a case of');
