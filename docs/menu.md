# Main menu

`Igne\Nova\Menu\GroupedMenu` builds Nova's sidebar from your resources' groups:

1. Nova's dashboard section: a link for one dashboard, a collapsable section for several;
2. the sections you prepend;
3. a collapsable section per group, in the order your group enum declares its cases;
4. the sections you append.

Nova keeps one main menu callback, so the app owns it and calls the builder from it. That keeps app-specific menus, such as an empty menu while a user still has to set up two-factor authentication, in the app.

## Set it up

Create a string-backed enum anywhere in `app/` that implements `Igne\Nova\Menu\Contracts\NovaMenuGroup`. The package finds it on its own. The order of its cases is the order of the sections; resources without a group, or with a group the enum doesn't know, are listed in the `fallback()` group.

```php
use Igne\Nova\Menu\Contracts\NovaMenuGroup;

enum NovaGroup: string implements NovaMenuGroup
{
    case USERS = 'users';
    case CONTENT = 'content';
    case RESOURCES = 'resources';

    public static function fallback(): static
    {
        return self::RESOURCES;
    }

    public function label(): string
    {
        return __("nova.groups.{$this->value}");
    }

    public function icon(): string
    {
        return match ($this) {
            self::USERS => 'users',
            self::CONTENT => 'document-text',
            self::RESOURCES => 'rectangle-stack',
        };
    }
}
```

Put a resource in a group by implementing `Igne\Nova\Menu\Contracts\HasMenuGroup`:

```php
use Igne\Nova\Menu\Contracts\HasMenuGroup;
use Igne\Nova\Menu\Contracts\NovaMenuGroup;

final class User extends Resource implements HasMenuGroup
{
    public static function menuGroup(): NovaMenuGroup
    {
        return NovaGroup::USERS;
    }
}
```

`menuGroup()` must return a case of the menu's enum; a case of another enum throws a `MenuGroupException`. A resource you can't change, such as one from another package, can still join a group with Nova's own `public static $group = 'users';`, matched against the enum's values.

Build the menu in your `NovaServiceProvider::boot()`:

```php
use Igne\Nova\Menu\GroupedMenu;

Nova::mainMenu(fn (Request $request): array => GroupedMenu::make($request)
    ->append(MenuSection::make(__('Settings'), [MenuItem::link(__('General'), '/settings')])->icon('cog'))
    ->sections());
```

### How the enum is found

The package scans the `igne-nova.menu.discover` folders (`app/` by default) for an enum that implements `NovaMenuGroup`. It loads only the files that mention `NovaMenuGroup` and keeps the result for the rest of the process. It throws a `MenuGroupException` when it finds none or more than one. In either case, or when the enum lives outside `app/`, name it in the config, which skips the scan:

```php
// config/igne-nova.php
'menu' => [
    'groups' => \App\Nova\NovaGroup::class,
],
```

| Method | Does |
|---|---|
| `prepend(MenuSection ...$sections)`, `prependWhen(bool $condition, MenuSection ...$sections)` | Adds sections after the dashboards, before the groups. |
| `append(MenuSection ...$sections)`, `appendWhen(bool $condition, MenuSection ...$sections)` | Adds sections after the groups. |
| `withoutDashboards()` | Leaves Nova's dashboard section out, to place the dashboards yourself. |

Every group section is collapsable and starts open; Nova remembers each user's choice. To start some sections closed, also implement `Igne\Nova\Menu\Contracts\CollapsedByDefault` on the enum:

```php
public function collapsedByDefault(): bool
{
    return $this === self::RESOURCES;
}
```

Within a section, resources are listed in Nova's order: by label, or by `Nova::sortResourcesBy()`. A resource's own `menu()` method is not used.

## Badges

A resource shows a badge next to its menu item when it implements `Igne\Nova\Menu\Contracts\HasMenuBadge`:

```php
use Igne\Nova\BadgeType;
use Igne\Nova\Menu\Contracts\HasMenuBadge;
use Igne\Nova\Menu\MenuBadge;

final class Invitation extends Resource implements HasMenuBadge
{
    public static function menuBadge(): ?MenuBadge
    {
        return MenuBadge::count(
            fn (): int => \App\Models\Invitation::query()->pending()->count(),
            'nova.badges.pending', // a translation key or text, with a :count placeholder
            BadgeType::INFO,
        );
    }
}
```

Nova rebuilds the menu on every page visit, so the count is cached for `igne-nova.menu.badge_ttl` seconds (300 by default) and is indicative: it can be that long behind. A count of zero shows no badge. The cache is shared by all users; when the count depends on the user, such as records they may see, cache it per user:

```php
return MenuBadge::count(fn (): int => …)->perUser();
```
