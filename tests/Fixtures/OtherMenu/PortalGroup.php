<?php

declare(strict_types=1);

namespace Igne\Nova\Tests\Fixtures\OtherMenu;

use Igne\Nova\Menu\Contracts\NovaMenuGroup;

/**
 * A second menu group enum, for the discovery tests.
 */
enum PortalGroup: string implements NovaMenuGroup
{
    case GENERAL = 'general';

    public static function fallback(): static
    {
        return self::GENERAL;
    }

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public function icon(): string
    {
        return 'home';
    }
}
