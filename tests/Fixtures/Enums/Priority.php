<?php

declare(strict_types=1);

namespace Igne\Nova\Tests\Fixtures\Enums;

use Igne\Nova\Fields\Concerns\TranslatesBadgeLabel;
use Igne\Nova\Fields\Contracts\BadgeEnum;

enum Priority: int implements BadgeEnum
{
    use TranslatesBadgeLabel;

    case LOW = 1;
    case HIGH = 2;
}
