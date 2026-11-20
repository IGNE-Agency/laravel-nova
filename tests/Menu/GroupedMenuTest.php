<?php

declare(strict_types=1);

use Igne\Nova\Menu\GroupedMenu;
use Igne\Nova\Tests\Fixtures\Menu\NovaGroup;
use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuSection;

it('lists the dashboards first, then a section per group in the order the enum declares its cases', function (): void {
    $menu = $this->serializedMenu(static fn (Request $request): GroupedMenu => GroupedMenu::make($request, NovaGroup::class));

    expect(array_column($menu, 'name'))->toBe(['Main', 'Content', 'Users', 'Resources', 'Settings']);
});

it('lists a resource without a known group in the fallback group', function (): void {
    $menu = $this->serializedMenu(static fn (Request $request): GroupedMenu => GroupedMenu::make($request, NovaGroup::class));

    $resources = collect($menu)->firstWhere('name', 'Resources');

    expect(array_column($resources['items'], 'name'))->toBe(['Notes']);
});

it('lists the resources of a group in Nova\'s order, by label', function (): void {
    $menu = $this->serializedMenu(static fn (Request $request): GroupedMenu => GroupedMenu::make($request, NovaGroup::class));

    $users = collect($menu)->firstWhere('name', 'Users');

    expect(array_column($users['items'], 'name'))->toBe(['Invitations', 'Users'])
        ->and($users['icon'])->toBe('users');
});

it('makes the sections collapsable and closes the groups that ask for it', function (): void {
    $menu = collect($this->serializedMenu(static fn (Request $request): GroupedMenu => GroupedMenu::make($request, NovaGroup::class)));

    expect($menu->firstWhere('name', 'Users'))->toMatchArray(['collapsable' => true, 'collapsedByDefault' => false])
        ->and($menu->firstWhere('name', 'Settings'))->toMatchArray(['collapsable' => true, 'collapsedByDefault' => true]);
});

it('leaves the dashboards out on request', function (): void {
    $menu = $this->serializedMenu(static fn (Request $request): GroupedMenu => GroupedMenu::make($request, NovaGroup::class)->withoutDashboards());

    expect(array_column($menu, 'name'))->not->toContain('Main');
});

it('adds the prepended sections after the dashboards and the appended ones after the groups', function (): void {
    $menu = $this->serializedMenu(static fn (Request $request): GroupedMenu => GroupedMenu::make($request, NovaGroup::class)
        ->prepend(MenuSection::make('First'))
        ->prependWhen(false, MenuSection::make('Skipped'))
        ->append(MenuSection::make('Last'), MenuSection::make('Very last'))
        ->appendWhen(true, MenuSection::make('Conditional')));

    expect(array_column($menu, 'name'))->toBe(['Main', 'First', 'Content', 'Users', 'Resources', 'Settings', 'Last', 'Very last', 'Conditional']);
});
