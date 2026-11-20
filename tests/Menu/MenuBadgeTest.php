<?php

declare(strict_types=1);

use Igne\Nova\BadgeType;
use Igne\Nova\Menu\GroupedMenu;
use Igne\Nova\Menu\MenuBadge;
use Igne\Nova\Tests\Fixtures\Menu\NovaGroup;
use Igne\Nova\Tests\Fixtures\Nova\Invitation;
use Igne\Nova\Tests\Fixtures\User;
use Laravel\Nova\Badge;
use Laravel\Nova\Http\Requests\NovaRequest;

afterEach(function (): void {
    Invitation::$badge = null;
});

/**
 * @return array<string, mixed>|null
 */
function invitationsBadge(array $menu): ?array
{
    $users = collect($menu)->firstWhere('name', 'Users');

    return collect($users['items'])->firstWhere('name', 'Invitations')['badge'];
}

function buildMenu(): Closure
{
    return static fn (NovaRequest $request): GroupedMenu => GroupedMenu::make($request, NovaGroup::class);
}

it('shows the count with its label and type', function (): void {
    Invitation::$badge = static fn (): MenuBadge => MenuBadge::count(static fn (): int => 3, 'new :count', BadgeType::WARNING);

    $badge = invitationsBadge($this->serializedMenu(buildMenu()));

    expect($badge)->toMatchArray(['value' => 'new 3', 'typeClass' => Badge::$types['warning']]);
});

it('shows no badge when the count is zero', function (): void {
    Invitation::$badge = static fn (): MenuBadge => MenuBadge::count(static fn (): int => 0);

    expect(invitationsBadge($this->serializedMenu(buildMenu())))->toBeNull();
});

it('shows no badge for a resource without one', function (): void {
    expect(invitationsBadge($this->serializedMenu(buildMenu())))->toBeNull();
});

it('caches the count for all users', function (): void {
    $counted = 0;
    Invitation::$badge = static function () use (&$counted): MenuBadge {
        return MenuBadge::count(static function () use (&$counted): int {
            return ++$counted;
        });
    };

    $this->serializedMenu(buildMenu());
    $badge = invitationsBadge($this->serializedMenu(buildMenu()));

    expect($counted)->toBe(1)->and($badge['value'])->toBe('1');
});

it('caches the count per user on request', function (): void {
    $counted = 0;
    Invitation::$badge = static function () use (&$counted): MenuBadge {
        return MenuBadge::count(static function () use (&$counted): int {
            return ++$counted;
        })->perUser();
    };
    $firstUser = User::factory()->create();

    $this->serializedMenu(buildMenu(), $firstUser);
    $this->serializedMenu(buildMenu(), User::factory()->create());
    $badge = invitationsBadge($this->serializedMenu(buildMenu(), $firstUser));

    expect($counted)->toBe(2)->and($badge['value'])->toBe('1');
});
