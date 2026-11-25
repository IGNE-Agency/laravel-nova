<?php

declare(strict_types=1);

namespace Igne\Nova\Menu\Contracts;

use Igne\Nova\Menu\MenuBadge;

/**
 * A Nova resource that shows a badge, such as a count of new records, next to
 * its menu item.
 */
interface HasMenuBadge
{
    /**
     * The resource's badge, or null for none.
     */
    public static function menuBadge(): ?MenuBadge;
}
