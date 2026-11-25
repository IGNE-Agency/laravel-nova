<?php

declare(strict_types=1);

namespace Igne\Nova\Tests\Fixtures\Nova;

use Igne\Nova\Menu\Contracts\HasMenuGroup;
use Igne\Nova\Menu\Contracts\NovaMenuGroup;
use Igne\Nova\Tests\Fixtures\Menu\NovaGroup;
use Igne\Nova\Tests\Fixtures\User as UserModel;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource as NovaResource;

/**
 * @extends NovaResource<UserModel>
 */
final class User extends NovaResource implements HasMenuGroup
{
    /**
     * @var class-string<UserModel>
     */
    public static $model = UserModel::class;

    /**
     * @var string
     */
    public static $title = 'name';

    public static function menuGroup(): NovaMenuGroup
    {
        return NovaGroup::USERS;
    }

    /**
     * @return array<int, mixed>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Name')->sortable(),
            Text::make('Email'),
        ];
    }
}
