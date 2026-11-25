<?php

declare(strict_types=1);

namespace Igne\Nova\Fields\Contracts;

use Igne\Nova\BadgeType;

/**
 * Gives a badge enum's cases one of Nova's badge colours; without it every
 * case shows in the info colour.
 */
interface HasBadgeType
{
    public function badgeType(): BadgeType;
}
