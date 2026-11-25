<?php

declare(strict_types=1);

namespace Igne\Nova\Fields\Contracts;

use BackedEnum;

/**
 * An enum that an EnumBadge shows. Only the label is required; the colour is
 * opt-in through HasBadgeType or HasBadgeClasses.
 *
 * label() has the same signature as HasLabel in igne-agency/laravel-permissions,
 * so one enum can implement both.
 */
interface BadgeEnum extends BackedEnum
{
    public function label(): string;
}
