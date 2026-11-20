<?php

declare(strict_types=1);

namespace Igne\Nova\Fields;

use Igne\Nova\BadgeType;
use Igne\Nova\Fields\Contracts\BadgeEnum;
use Igne\Nova\Fields\Contracts\HasBadgeClasses;
use Laravel\Nova\Fields\Badge;

/**
 * Nova's Badge field for an enum attribute: every case gets its label and its
 * colour from the enum, so no case can be missing from the mapping, which
 * makes Nova's Badge throw when the page renders.
 *
 * An empty value shows "Unknown" in the info colour, so the badge can back a
 * nullable column; the field's filter lists it as well.
 */
final class EnumBadge
{
    /**
     * @param  string|(callable(mixed, mixed, ?string): mixed)  $attribute  a callable returns the case's value
     * @param  class-string<BadgeEnum>  $enum
     */
    public static function make(string $name, string|callable $attribute, string $enum): Badge
    {
        $map = [];
        $types = [];

        foreach ($enum::cases() as $case) {
            $type = $case instanceof HasBadgeClasses ? 'igne-nova-'.$case->value : $case->badgeType()->value;

            $map[$case->value] = $type;

            if ($case instanceof HasBadgeClasses) {
                $types[$type] = $case->badgeClasses();
            }
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
        $case = (\is_string($value) && $value !== '') || \is_int($value) ? $enum::tryFrom($value) : null;

        if ($case === null) {
            $unknown = __('igne-nova::fields.unknown');

            return \is_string($unknown) ? $unknown : 'Unknown';
        }

        return $case->label();
    }
}
