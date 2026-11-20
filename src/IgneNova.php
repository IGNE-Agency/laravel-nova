<?php

declare(strict_types=1);

namespace Igne\Nova;

use Closure;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * The package's hooks, for app behaviour the package cannot know.
 */
final class IgneNova
{
    /**
     * @var (Closure(Authenticatable): ?string)|null
     */
    private static ?Closure $timezoneResolver = null;

    /**
     * Resolve the timezone Nova shows dates in for a user. A null result, or no
     * resolver, falls back to the app's timezone.
     *
     * @param  (Closure(Authenticatable): ?string)|null  $resolver
     */
    public static function resolveTimezoneUsing(?Closure $resolver): void
    {
        self::$timezoneResolver = $resolver;
    }

    public static function timezoneFor(?Authenticatable $user, string $fallback): string
    {
        if ($user === null || self::$timezoneResolver === null) {
            return $fallback;
        }

        return (self::$timezoneResolver)($user) ?? $fallback;
    }

    /**
     * Forget the hooks, between tests or Octane requests.
     */
    public static function flushState(): void
    {
        self::$timezoneResolver = null;
    }
}
