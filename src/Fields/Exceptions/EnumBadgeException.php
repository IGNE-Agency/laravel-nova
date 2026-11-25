<?php

declare(strict_types=1);

namespace Igne\Nova\Fields\Exceptions;

use Igne\Nova\Fields\Contracts\BadgeEnum;
use LogicException;

/**
 * An EnumBadge is set up wrong, or its column holds a value its enum does not
 * have. A developer error, so the message is English and not translated.
 */
final class EnumBadgeException extends LogicException
{
    public static function notABadgeEnum(string $class): self
    {
        return new self(\sprintf('EnumBadge needs an enum that implements %s, [%s] given.', BadgeEnum::class, $class));
    }

    /**
     * @param  class-string<BadgeEnum>  $enum
     */
    public static function unknownCase(string $enum, mixed $value): self
    {
        $values = array_map(static fn (BadgeEnum $case): string => (string) $case->value, $enum::cases());

        return new self(\sprintf(
            'EnumBadge got [%s], which is not a case of %s. Its cases are: %s. Add the case, or migrate the rows that still hold the old value.',
            \is_int($value) || \is_string($value) ? $value : get_debug_type($value),
            $enum,
            implode(', ', $values),
        ));
    }
}
