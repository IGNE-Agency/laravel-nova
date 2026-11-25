<?php

declare(strict_types=1);

namespace Igne\Nova\Tests\Fixtures\Nova;

use Igne\Nova\Menu\Contracts\HasMenuGroup;
use Igne\Nova\Menu\Contracts\NovaMenuGroup;
use Igne\Nova\Resources\Concerns\IgnoresUnknownSortColumns;
use Igne\Nova\Resources\Concerns\TranslatesLabels;
use Igne\Nova\Tests\Fixtures\Menu\NovaGroup;
use Igne\Nova\Tests\Fixtures\User as UserModel;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource as NovaResource;

/**
 * A resource of the menu and resource helper tests, with the package's
 * resource traits. Its model does not matter.
 *
 * @extends NovaResource<UserModel>
 */
final class Article extends NovaResource implements HasMenuGroup
{
    use IgnoresUnknownSortColumns;
    use TranslatesLabels;

    /**
     * @var class-string<UserModel>
     */
    public static $model = UserModel::class;

    public static function menuGroup(): NovaMenuGroup
    {
        return NovaGroup::CONTENT;
    }

    /**
     * @return array<int, mixed>
     */
    public function fields(NovaRequest $request): array
    {
        return [ID::make(), Text::make('Name')->sortable()];
    }
}
