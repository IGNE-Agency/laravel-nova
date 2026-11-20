<?php

declare(strict_types=1);

namespace Igne\Nova\Branding;

use InvalidArgumentException;

/**
 * Turns the brand colour into Nova's primary colour scale: "r, g, b" strings
 * keyed by shade. One hex colour becomes shade 500 and the other shades are
 * mixed from it toward white and black; a full scale of hex colours is
 * converted as given.
 */
final class BrandColors
{
    /**
     * How far each shade is mixed toward white (negative) or black (positive).
     *
     * @var array<int, float>
     */
    private const array MIX = [
        50 => -0.95,
        100 => -0.9,
        200 => -0.75,
        300 => -0.6,
        400 => -0.3,
        500 => 0.0,
        600 => 0.15,
        700 => 0.3,
        800 => 0.45,
        900 => 0.6,
        950 => 0.75,
    ];

    /**
     * @param  string|array<int|string, string>  $color  one hex colour, or hex colours keyed by shade
     * @return array<int|string, string>
     */
    public static function scale(string|array $color): array
    {
        if (\is_array($color)) {
            $scale = [];

            foreach ($color as $shade => $hex) {
                $scale[$shade] = self::rgbString(self::rgb($hex));
            }

            return $scale;
        }

        $base = self::rgb($color);
        $scale = [];

        foreach (self::MIX as $shade => $mix) {
            $scale[$shade] = self::rgbString(self::mix($base, $mix));
        }

        return $scale;
    }

    /**
     * @return array{int, int, int}
     */
    private static function rgb(string $hex): array
    {
        if (preg_match('/^#?([0-9a-f]{6})$/i', $hex, $matches) !== 1) {
            throw new InvalidArgumentException(\sprintf('The brand colour [%s] is not a six-digit hex colour.', $hex));
        }

        return [
            (int) hexdec(substr($matches[1], 0, 2)),
            (int) hexdec(substr($matches[1], 2, 2)),
            (int) hexdec(substr($matches[1], 4, 2)),
        ];
    }

    /**
     * @param  array{int, int, int}  $rgb
     * @return array{int, int, int}
     */
    private static function mix(array $rgb, float $mix): array
    {
        $target = $mix < 0 ? 255 : 0;
        $weight = abs($mix);

        return [
            (int) round($rgb[0] + ($target - $rgb[0]) * $weight),
            (int) round($rgb[1] + ($target - $rgb[1]) * $weight),
            (int) round($rgb[2] + ($target - $rgb[2]) * $weight),
        ];
    }

    /**
     * @param  array{int, int, int}  $rgb
     */
    private static function rgbString(array $rgb): string
    {
        return implode(', ', $rgb);
    }
}
