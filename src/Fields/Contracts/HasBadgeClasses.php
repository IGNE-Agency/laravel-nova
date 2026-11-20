<?php

declare(strict_types=1);

namespace Igne\Nova\Fields\Contracts;

/**
 * Gives a badge enum's cases their own CSS classes, such as brand colours,
 * instead of the colour of their badge type.
 */
interface HasBadgeClasses
{
    public function badgeClasses(): string;
}
