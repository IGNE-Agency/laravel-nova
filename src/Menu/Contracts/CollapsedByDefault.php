<?php

declare(strict_types=1);

namespace Igne\Nova\Menu\Contracts;

/**
 * Lets a menu group enum close some of its sections until the user opens them.
 * Without it every section starts open. Nova remembers each user's choice.
 */
interface CollapsedByDefault
{
    public function collapsedByDefault(): bool;
}
