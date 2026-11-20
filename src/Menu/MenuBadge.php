<?php

declare(strict_types=1);

namespace Igne\Nova\Menu;

use Closure;
use Igne\Nova\BadgeType;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Cache\Repository as Cache;
use Laravel\Nova\Badge;
use LogicException;

/**
 * A count next to a resource's menu item. Nova rebuilds the menu on every page
 * visit, so the count is cached: shared by all users, or per user when the
 * count depends on who is looking.
 */
final class MenuBadge
{
    private bool $perUser = false;

    /**
     * @param  Closure(): int  $count
     */
    private function __construct(
        private readonly Closure $count,
        private readonly string $label,
        private readonly BadgeType $type,
    ) {}

    /**
     * @param  Closure(): int  $count  the query that counts the records
     * @param  string  $label  the badge's text or translation key, with a :count placeholder
     */
    public static function count(Closure $count, string $label = ':count', BadgeType $type = BadgeType::INFO): self
    {
        return new self($count, $label, $type);
    }

    /**
     * Cache the count per user instead of once for all users.
     */
    public function perUser(): self
    {
        $this->perUser = true;

        return $this;
    }

    /**
     * Get Nova's badge, or null when the count is zero.
     */
    public function resolve(Cache $cache, string $key, int $ttl, ?Authenticatable $user): ?Badge
    {
        if ($this->perUser && $user !== null) {
            $key .= '.user.'.$this->userKey($user);
        }

        $count = (int) $cache->remember($key, $ttl, $this->count);

        if ($count === 0) {
            return null;
        }

        return Badge::make(__($this->label, ['count' => $count]), $this->type->value);
    }

    private function userKey(Authenticatable $user): string
    {
        $identifier = $user->getAuthIdentifier();

        if (! \is_int($identifier) && ! \is_string($identifier)) {
            throw new LogicException('A per-user menu badge needs a user with an integer or string identifier.');
        }

        return (string) $identifier;
    }
}
