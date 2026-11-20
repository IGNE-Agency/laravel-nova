<?php

declare(strict_types=1);

namespace Igne\Nova\Menu\Contracts;

use BackedEnum;

/**
 * A group of the sidebar, implemented by the app's string-backed enum. Resources join a
 * group with Nova's own `public static $group = NovaGroup::USERS->value;`.
 *
 * The sections render in the order the enum declares its cases: move a case
 * to move its section.
 */
interface MenuGroup extends BackedEnum
{
    /**
     * The group resources without a known group are listed in.
     */
    public static function fallback(): static;

    /**
     * The section's title.
     */
    public function label(): string;

    /**
     * The section's Heroicon name, such as "users".
     */
    public function icon(): string;
}
