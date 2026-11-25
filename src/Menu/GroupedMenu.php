<?php

declare(strict_types=1);

namespace Igne\Nova\Menu;

use Igne\Nova\Menu\Contracts\CollapsedByDefault;
use Igne\Nova\Menu\Contracts\HasMenuBadge;
use Igne\Nova\Menu\Contracts\HasMenuGroup;
use Igne\Nova\Menu\Contracts\NovaMenuGroup;
use Igne\Nova\Menu\Exceptions\MenuGroupException;
use Illuminate\Config\Repository as Config;
use Illuminate\Container\Container;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\Resource as NovaResource;
use Laravel\Nova\Tools\Dashboard;

/**
 * Builds Nova's sidebar: the dashboards, then a collapsable section per group
 * of the app's {@see NovaMenuGroup} enum, then the sections the app appends.
 * The app calls it from its own Nova::mainMenu() callback:
 *
 *     Nova::mainMenu(fn (Request $request): array => GroupedMenu::make($request)->sections());
 */
final class GroupedMenu
{
    /**
     * @var list<MenuSection>
     */
    private array $prepended = [];

    /**
     * @var list<MenuSection>
     */
    private array $appended = [];

    private bool $withDashboards = true;

    /**
     * @param  class-string<NovaMenuGroup>  $groups
     */
    private function __construct(
        private readonly Request $request,
        private readonly string $groups,
        private readonly Cache $cache,
        private readonly int $badgeTtl,
    ) {}

    public static function make(Request $request): self
    {
        $container = Container::getInstance();

        return new self(
            $request,
            $container->make(MenuGroupDiscovery::class)->enum(),
            $container->make(Cache::class),
            $container->make(Config::class)->integer('igne-nova.menu.badge_ttl', 300),
        );
    }

    /**
     * Add sections before the groups, after the dashboards.
     */
    public function prepend(MenuSection ...$sections): self
    {
        $this->prepended = [...$this->prepended, ...array_values($sections)];

        return $this;
    }

    public function prependWhen(bool $condition, MenuSection ...$sections): self
    {
        return $condition ? $this->prepend(...$sections) : $this;
    }

    /**
     * Add sections after the groups.
     */
    public function append(MenuSection ...$sections): self
    {
        $this->appended = [...$this->appended, ...array_values($sections)];

        return $this;
    }

    public function appendWhen(bool $condition, MenuSection ...$sections): self
    {
        return $condition ? $this->append(...$sections) : $this;
    }

    /**
     * Leave Nova's dashboard section out, to place the dashboards yourself.
     */
    public function withoutDashboards(): self
    {
        $this->withDashboards = false;

        return $this;
    }

    /**
     * @return list<MenuSection>
     */
    public function sections(): array
    {
        return array_values(array_filter([
            $this->withDashboards ? $this->dashboardSection() : null,
            ...$this->prepended,
            ...$this->groupSections(),
            ...$this->appended,
        ]));
    }

    private function dashboardSection(): ?MenuSection
    {
        $section = (new Dashboard)->menu($this->request);

        return $section instanceof MenuSection ? $section : null;
    }

    /**
     * @return list<MenuSection>
     */
    private function groupSections(): array
    {
        $itemsByGroup = [];

        foreach (Nova::resourcesForNavigation($this->request) as $resource) {
            // Nova types the list as a bare array; it only holds resource classes.
            if (! \is_string($resource) || ! is_subclass_of($resource, NovaResource::class)) {
                continue;
            }

            $itemsByGroup[$this->groupOf($resource)->value][] = $this->menuItem($resource);
        }

        $sections = [];

        foreach ($this->groups::cases() as $group) {
            if (isset($itemsByGroup[$group->value])) {
                $sections[] = $this->section($group, $itemsByGroup[$group->value]);
            }
        }

        return $sections;
    }

    /**
     * A resource of another package can still join a group with Nova's string $group.
     *
     * @param  class-string<NovaResource<Model>>  $resource
     */
    private function groupOf(string $resource): NovaMenuGroup
    {
        $groups = $this->groups;

        if (! is_subclass_of($resource, HasMenuGroup::class)) {
            return $groups::tryFrom((string) $resource::group()) ?? $groups::fallback();
        }

        $group = $resource::menuGroup();

        if (! $group instanceof $groups) {
            throw MenuGroupException::foreignGroup($resource, $group, $groups);
        }

        return $group;
    }

    /**
     * @param  array<int, MenuItem>  $items
     */
    private function section(NovaMenuGroup $group, array $items): MenuSection
    {
        $section = MenuSection::make($group->label(), $items)->icon($group->icon());

        if ($group instanceof CollapsedByDefault && $group->collapsedByDefault()) {
            return $section->collapsedByDefault();
        }

        return $section->collapsable();
    }

    /**
     * @param  class-string<NovaResource<Model>>  $resource
     */
    private function menuItem(string $resource): MenuItem
    {
        $item = MenuItem::resource($resource);

        if (! is_subclass_of($resource, HasMenuBadge::class)) {
            return $item;
        }

        return $item->withBadge(fn () => $resource::menuBadge()?->resolve(
            $this->cache,
            'igne-nova.menu-badge.'.$resource::uriKey(),
            $this->badgeTtl,
            $this->request->user(),
        ) ?? false);
    }
}
