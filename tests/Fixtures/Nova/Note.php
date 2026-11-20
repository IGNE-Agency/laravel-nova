<?php

declare(strict_types=1);

namespace Igne\Nova\Tests\Fixtures\Nova;

use Igne\Nova\Tests\Fixtures\User as UserModel;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource as NovaResource;

/**
 * A resource of the menu tests; its model does not matter.
 *
 * @extends NovaResource<UserModel>
 */
final class Note extends NovaResource
{
    /**
     * @var class-string<UserModel>
     */
    public static $model = UserModel::class;

    /**
     * @return array<int, mixed>
     */
    public function fields(NovaRequest $request): array
    {
        return [ID::make()];
    }
}
