<?php

declare(strict_types=1);

namespace Igne\Nova\Menu\Exceptions;

use Igne\Nova\Menu\Contracts\NovaMenuGroup;
use LogicException;

/**
 * The app's menu groups are set up wrong. A developer error, so the message is English and
 * not translated.
 */
final class MenuGroupException extends LogicException
{
    public static function notAnEnum(string $class): self
    {
        return new self(\sprintf('[igne-nova.menu.groups] must be an enum that implements %s, [%s] given.', NovaMenuGroup::class, $class));
    }

    public static function notADirectory(string $path): self
    {
        return new self(\sprintf('[igne-nova.menu.discover] holds [%s], which is not a directory.', $path));
    }

    /**
     * @param  list<string>  $paths
     */
    public static function noneFound(array $paths): self
    {
        return new self(\sprintf('No enum that implements %s was found in [%s]. Add one, or set [igne-nova.menu.groups].', NovaMenuGroup::class, implode(', ', $paths)));
    }

    /**
     * @param  list<string>  $enums
     */
    public static function severalFound(array $enums): self
    {
        return new self(\sprintf('Several enums implement %s: [%s]. Set [igne-nova.menu.groups] to the one the menu uses.', NovaMenuGroup::class, implode(', ', $enums)));
    }

    public static function foreignGroup(string $resource, NovaMenuGroup $group, string $enum): self
    {
        return new self(\sprintf('%s::menuGroup() returns a case of [%s], but the menu uses [%s].', $resource, $group::class, $enum));
    }
}
