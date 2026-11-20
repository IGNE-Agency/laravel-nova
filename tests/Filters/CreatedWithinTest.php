<?php

declare(strict_types=1);

use Igne\Nova\Filters\CreatedWithin;
use Igne\Nova\Tests\Fixtures\User;
use Illuminate\Support\Facades\Date;
use Laravel\Nova\Http\Requests\NovaRequest;

beforeEach(function (): void {
    Date::setTestNow('2026-11-20 15:00:00');

    foreach (['2026-11-20 00:00:00', '2026-11-14 00:00:00', '2026-11-13 23:59:59', '2026-10-01 12:00:00'] as $createdAt) {
        User::factory()->create(['created_at' => $createdAt]);
    }
});

/**
 * @return list<string>
 */
function createdWithin(CreatedWithin $filter, mixed $value): array
{
    return $filter->apply(new NovaRequest, User::query(), $value)
        ->orderBy('created_at')
        ->pluck('created_at')
        ->map(static fn ($createdAt): string => (string) $createdAt)
        ->all();
}

it('filters on today', function (): void {
    expect(createdWithin(new CreatedWithin, '1'))->toBe(['2026-11-20 00:00:00']);
});

it('filters on today and the days before it', function (): void {
    expect(createdWithin(new CreatedWithin, '7'))->toBe(['2026-11-14 00:00:00', '2026-11-20 00:00:00']);
});

it('leaves the query alone for a cleared or invalid value', function (mixed $value): void {
    expect(createdWithin(new CreatedWithin, $value))->toHaveCount(4);
})->with([
    'null' => [null],
    'empty string' => [''],
    'cleared to an array' => [[]],
    'zero' => ['0'],
    'not a number' => ['abc'],
]);

it('offers its windows with labels', function (): void {
    expect((new CreatedWithin(days: [1, 14]))->options(new NovaRequest))->toBe([
        __('igne-nova::filters.today') => '1',
        __('igne-nova::filters.last_days', ['count' => 14]) => '14',
    ]);
});

it('has a key per column, so a resource can filter on several', function (): void {
    expect((new CreatedWithin)->key())->not->toBe((new CreatedWithin('updated_at'))->key());
});

it('takes the given name', function (): void {
    expect((new CreatedWithin(label: 'Sent'))->name())->toBe('Sent')
        ->and((new CreatedWithin)->name())->toBe(__('igne-nova::filters.period'));
});
