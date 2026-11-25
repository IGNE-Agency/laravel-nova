<?php

declare(strict_types=1);

namespace Igne\Nova\Tests\Fixtures\Nova;

use Igne\Nova\Menu\Contracts\HasMenuGroup;
use Igne\Nova\Menu\Contracts\NovaMenuGroup;
use Igne\Nova\Tests\Fixtures\Menu\NovaGroup;
use Igne\Nova\Tests\Fixtures\User as UserModel;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource as NovaResource;

/**
 * A resource of the menu tests; its model does not matter.
 *
 * @extends NovaResource<UserModel>
 */
final class Setting extends NovaResource implements HasMenuGroup
{
    /**
     * @var class-string<UserModel>
     */
    public static $model = UserModel::class;

    public static function menuGroup(): NovaMenuGroup
    {
        return NovaGroup::SETTINGS;
    }

    /**
     * @return array<int, mixed>
     */
    public function fields(NovaRequest $request): array
    {
        return [ID::make()];
    }
}
