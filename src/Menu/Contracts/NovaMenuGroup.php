<?php

declare(strict_types=1);

namespace Igne\Nova\Menu\Contracts;

use BackedEnum;

/**
 * A group of the sidebar, implemented by one string-backed enum of the app. The package finds
 * that enum itself; resources join one of its groups through {@see HasMenuGroup}.
 *
 * The sections render in the order the enum declares its cases: move a case
 * to move its section.
 */
interface NovaMenuGroup extends BackedEnum
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
