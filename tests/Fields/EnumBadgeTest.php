<?php

declare(strict_types=1);

use Igne\Nova\BadgeType;
use Igne\Nova\Fields\EnumBadge;
use Igne\Nova\Fields\Exceptions\EnumBadgeException;
use Igne\Nova\Tests\Fixtures\Enums\Brand;
use Igne\Nova\Tests\Fixtures\Enums\Priority;
use Igne\Nova\Tests\Fixtures\Enums\Status;
use Igne\Nova\Tests\Fixtures\User;
use Laravel\Nova\Badge;

it('shows each case with its label and the colour of its badge type', function (): void {
    $badge = EnumBadge::make('Status', 'name', Status::class);
    $badge->resolve(new User(['name' => 'closed']));

    expect($badge->resolveBadgeClasses())->toBe(Badge::$types['danger'])
        ->and($badge->resolveLabel())->toBe('Closed');
});

it('shows a case with its own classes when the enum gives them', function (): void {
    $badge = EnumBadge::make('Brand', 'name', Brand::class);
    $badge->resolve(new User(['name' => 'teal']));

    expect($badge->resolveBadgeClasses())->toBe('text-teal-700 bg-teal-100');
});

it('shows an empty value as unknown in the info colour', function (?string $value): void {
    $badge = EnumBadge::make('Status', 'name', Status::class);
    $badge->resolve(new User(['name' => $value]));

    expect($badge->resolveBadgeClasses())->toBe(Badge::$types['info'])
        ->and($badge->resolveLabel())->toBe(__('igne-nova::fields.unknown'));
})->with([null, '']);

it('resolves a callable attribute', function (): void {
    $badge = EnumBadge::make('Status', static fn (): string => Status::OPEN->value, Status::class);
    $badge->resolve(new User);

    expect($badge->resolveBadgeClasses())->toBe(Badge::$types['success'])
        ->and($badge->resolveLabel())->toBe('Open');
});

it('offers every case and unknown in its filter', function (): void {
    $badge = EnumBadge::make('Status', 'name', Status::class);

    expect($badge->serializeForFilter()['options'])->toBe([
        ['value' => 'open', 'label' => 'Open'],
        ['value' => 'closed', 'label' => 'Closed'],
        ['value' => '', 'label' => __('igne-nova::fields.unknown')],
    ]);
});

it('shows a case in the info colour when the enum gives no badge type', function (): void {
    $badge = EnumBadge::make('Priority', 'name', Priority::class);
    $badge->resolve(new User(['name' => 2]));

    expect($badge->resolveBadgeClasses())->toBe(Badge::$types['info']);
});

it('takes the label from the translations keyed by the enum and the value', function (): void {
    app('translator')->addLines(['enums.priority.2' => 'High'], 'en');

    $badge = EnumBadge::make('Priority', 'name', Priority::class);
    $badge->resolve(new User(['name' => 2]));

    expect($badge->resolveLabel())->toBe('High');
});

it('matches an integer case that the database returns as a string', function (): void {
    app('translator')->addLines(['enums.priority.1' => 'Low'], 'en');

    $badge = EnumBadge::make('Priority', 'name', Priority::class);
    $badge->resolve(new User(['name' => '1']));

    expect($badge->resolveLabel())->toBe('Low');
});

it('names the enum and its cases for a value that is not a case', function (): void {
    $badge = EnumBadge::make('Status', 'name', Status::class);
    $badge->resolve(new User(['name' => 'archived']));

    expect(fn (): array => $badge->jsonSerialize())->toThrow(
        EnumBadgeException::class,
        'EnumBadge got [archived], which is not a case of '.Status::class.'. Its cases are: open, closed.',
    );
});

it('refuses an enum that does not implement BadgeEnum', function (): void {
    expect(fn (): mixed => EnumBadge::make('Type', 'name', BadgeType::class))->toThrow(
        EnumBadgeException::class,
        'EnumBadge needs an enum that implements Igne\\Nova\\Fields\\Contracts\\BadgeEnum, ['.BadgeType::class.'] given.',
    );
});
