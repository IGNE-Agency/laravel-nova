<?php

declare(strict_types=1);

namespace Igne\Nova\Tests\Fixtures\Nova;

use Closure;
use Igne\Nova\Menu\Contracts\HasMenuBadge;
use Igne\Nova\Menu\MenuBadge;
use Igne\Nova\Tests\Fixtures\User as UserModel;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource as NovaResource;

/**
 * A resource with a menu badge, which the tests set.
 *
 * @extends NovaResource<UserModel>
 */
final class Invitation extends NovaResource implements HasMenuBadge
{
    /**
     * @var class-string<UserModel>
     */
    public static $model = UserModel::class;

    /**
     * @var string
     */
    public static $group = 'users';

    /**
     * @var (Closure(): ?MenuBadge)|null
     */
    public static ?Closure $badge = null;

    public static function menuBadge(): ?MenuBadge
    {
        return self::$badge === null ? null : (self::$badge)();
    }

    /**
     * @return array<int, mixed>
     */
    public function fields(NovaRequest $request): array
    {
        return [ID::make()];
    }
}
