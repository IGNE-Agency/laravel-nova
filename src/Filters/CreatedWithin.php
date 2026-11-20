<?php

declare(strict_types=1);

namespace Igne\Nova\Filters;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Date;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

/**
 * Filters records on a timestamp within the last days: "Today" for one day,
 * "Last 7 days" for today and the six days before it. For a from-to range,
 * use Nova's own filterable Date field instead.
 *
 * The key includes the column, so a resource can filter on several columns
 * with it.
 */
final class CreatedWithin extends Filter
{
    /**
     * @param  list<positive-int>  $days  the windows offered
     */
    public function __construct(
        private readonly string $column = 'created_at',
        private readonly array $days = [1, 7, 30],
        private readonly ?string $label = null,
    ) {}

    public function name(): string
    {
        $name = $this->label ?? __('igne-nova::filters.period');

        return \is_string($name) ? $name : 'Period';
    }

    public function key(): string
    {
        return 'created-within:'.$this->column;
    }

    /**
     * @param  Builder  $query
     * @param  mixed  $value
     */
    public function apply(NovaRequest $request, $query, $value): Builder
    {
        $days = filter_var($value, \FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

        if ($days === false) {
            return $query;
        }

        return $query->where($this->column, '>=', Date::now()->subDays($days - 1)->startOfDay());
    }

    /**
     * @return array<string, string>
     */
    public function options(NovaRequest $request): array
    {
        $options = [];

        foreach ($this->days as $days) {
            $label = $days === 1
                ? __('igne-nova::filters.today')
                : __('igne-nova::filters.last_days', ['count' => $days]);

            $options[\is_string($label) ? $label : (string) $days] = (string) $days;
        }

        return $options;
    }
}
