<?php

declare(strict_types=1);

namespace Igne\Nova\Menu\Contracts;

/**
 * A Nova resource that is listed under one of the app's menu groups.
 */
interface HasMenuGroup
{
    public static function menuGroup(): NovaMenuGroup;
}
