<?php

declare(strict_types=1);

namespace Igne\Nova\Fields\Contracts;

use BackedEnum;
use Igne\Nova\BadgeType;

/**
 * An enum that an EnumBadge shows: each case's label and its badge colour.
 */
interface BadgeEnum extends BackedEnum
{
    public function label(): string;

    public function badgeType(): BadgeType;
}
