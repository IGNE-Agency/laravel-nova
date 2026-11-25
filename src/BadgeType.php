<?php

declare(strict_types=1);

namespace Igne\Nova;

/**
 * Nova's built-in badge types, so a badge's colour is checked by the type
 * system instead of failing on a misspelt string when the page renders.
 */
enum BadgeType: string
{
    case SUCCESS = 'success';
    case INFO = 'info';
    case WARNING = 'warning';
    case DANGER = 'danger';
}
