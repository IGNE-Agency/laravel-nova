<?php

declare(strict_types=1);

namespace Igne\Nova\Resources\Concerns;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

/**
 * Nova puts the request's "orderBy" into "order by" verbatim, without checking
 * it against the sortable fields, so a bookmarked or stale sort on anything
 * that is not a column takes the whole index down with a SQL error. A relation
 * that used to be sortable is how that happens in practice.
 *
 * An unknown column is dropped rather than rejected: the admin asked for an
 * order the data cannot give, and the list they came for is worth more than
 * the sort they clicked. Columns a join or sub-select adds are unknown too, so
 * a resource that sorts on such an alias cannot use this trait.
 *
 * Use it on the app's base Nova resource.
 */
trait IgnoresUnknownSortColumns
{
    /**
     * Each table's column names per process, keyed "{connection}.{table}".
     * Nova renders an index on nearly every request, so this must not cost a
     * schema read each time.
     *
     * @var array<string, list<string>>
     */
    private static array $sortableColumns = [];

    /**
     * @param  array<string, string>  $orderings
     */
    protected static function applyOrderings(Builder $query, array $orderings): Builder
    {
        return parent::applyOrderings($query, array_filter(
            $orderings,
            static fn (string $column): bool => static::isSortableColumn($query, $column),
            \ARRAY_FILTER_USE_KEY,
        ));
    }

    /**
     * Whether the model's own table has the column. A qualified name such as
     * "posts.title" counts by its column.
     */
    private static function isSortableColumn(Builder $query, string $column): bool
    {
        $model = $query->getModel();
        $column = str_contains($column, '.') ? substr((string) strrchr($column, '.'), 1) : $column;
        $connection = (string) $model->getConnectionName();
        $table = $model->getTable();

        self::$sortableColumns[$connection.'.'.$table] ??= Schema::connection($model->getConnectionName())->getColumnListing($table);

        return \in_array($column, self::$sortableColumns[$connection.'.'.$table], true);
    }
}
