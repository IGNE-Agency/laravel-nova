<?php

declare(strict_types=1);

use Igne\Nova\Fields\EnumBadge;
use Igne\Nova\Tests\Fixtures\Enums\Brand;
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
