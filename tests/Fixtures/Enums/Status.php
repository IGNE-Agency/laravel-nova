<?php

declare(strict_types=1);

namespace Igne\Nova\Tests\Fixtures\Enums;

use Igne\Nova\BadgeType;
use Igne\Nova\Fields\Contracts\BadgeEnum;

enum Status: string implements BadgeEnum
{
    case OPEN = 'open';
    case CLOSED = 'closed';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public function badgeType(): BadgeType
    {
        return match ($this) {
            self::OPEN => BadgeType::SUCCESS,
            self::CLOSED => BadgeType::DANGER,
        };
    }
}
