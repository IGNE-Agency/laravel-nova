<?php

declare(strict_types=1);

namespace Igne\Nova\Fields;

use Igne\Nova\BadgeType;
use Igne\Nova\Fields\Contracts\BadgeEnum;
use Igne\Nova\Fields\Contracts\HasBadgeClasses;
use Igne\Nova\Fields\Contracts\HasBadgeType;
use Igne\Nova\Fields\Exceptions\EnumBadgeException;
use Laravel\Nova\Fields\Badge;

/**
 * Nova's Badge field for an enum attribute: every case gets its label and its
 * colour from the enum, so no case can be missing from the mapping, which
 * makes Nova's Badge throw when the page renders.
 *
 * An empty value shows "Unknown" in the info colour, so the badge can back a
 * nullable column; the field's filter lists it as well. A value that is not a
 * case throws an EnumBadgeException that names the enum and its cases, instead
 * of Nova's error about a missing type.
 */
final class EnumBadge
{
    /**
     * @param  string|(callable(mixed, mixed, ?string): mixed)  $attribute  a callable returns the case's value
     * @param  class-string  $enum  an enum that implements BadgeEnum
     */
    public static function make(string $name, string|callable $attribute, string $enum): Badge
    {
        if (! is_a($enum, BadgeEnum::class, true)) {
            throw EnumBadgeException::notABadgeEnum($enum);
        }

        $map = [];
        $types = [];

        foreach ($enum::cases() as $case) {
            if ($case instanceof HasBadgeClasses) {
                $type = 'igne-nova-'.$case->value;
                $map[$case->value] = $type;
                $types[$type] = $case->badgeClasses();

                continue;
            }

            $map[$case->value] = $case instanceof HasBadgeType ? $case->badgeType()->value : BadgeType::INFO->value;
        }

        $map[''] = BadgeType::INFO->value;

        return Badge::make($name, $attribute)
            ->map($map)
            ->addTypes($types)
            ->label(static fn (mixed $value): string => self::label($enum, $value));
    }

    /**
     * @param  class-string<BadgeEnum>  $enum
     */
    private static function label(string $enum, mixed $value): string
    {
        if ($value === null || $value === '') {
            $unknown = __('igne-nova::fields.unknown');

            return \is_string($unknown) ? $unknown : 'Unknown';
        }

        if (! \is_int($value) && ! \is_string($value)) {
            throw EnumBadgeException::unknownCase($enum, $value);
        }

        // Compared as strings: a driver can return an integer column as a string,
        // and tryFrom() rejects the wrong scalar type under strict types.
        foreach ($enum::cases() as $case) {
            if ((string) $case->value === (string) $value) {
                return $case->label();
            }
        }

        throw EnumBadgeException::unknownCase($enum, $value);
    }
}
