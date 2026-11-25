<?php

declare(strict_types=1);

namespace Igne\Nova\Tests\Fixtures\Enums;

use Igne\Nova\Fields\Contracts\BadgeEnum;
use Igne\Nova\Fields\Contracts\HasBadgeClasses;

enum Brand: string implements BadgeEnum, HasBadgeClasses
{
    case TEAL = 'teal';

    public function label(): string
    {
        return 'Teal';
    }

    public function badgeClasses(): string
    {
        return 'text-teal-700 bg-teal-100';
    }
}
