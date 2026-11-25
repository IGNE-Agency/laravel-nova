<?php

declare(strict_types=1);

namespace Igne\Nova\Tests\Fixtures\Menu;

use Igne\Nova\Menu\Contracts\CollapsedByDefault;
use Igne\Nova\Menu\Contracts\NovaMenuGroup;

enum NovaGroup: string implements CollapsedByDefault, NovaMenuGroup
{
    case CONTENT = 'content';
    case USERS = 'users';
    case RESOURCES = 'resources';
    case SETTINGS = 'settings';

    public static function fallback(): static
    {
        return self::RESOURCES;
    }

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public function icon(): string
    {
        return match ($this) {
            self::CONTENT => 'document-text',
            self::USERS => 'users',
            self::RESOURCES => 'rectangle-stack',
            self::SETTINGS => 'cog',
        };
    }

    public function collapsedByDefault(): bool
    {
        return $this === self::SETTINGS;
    }
}
