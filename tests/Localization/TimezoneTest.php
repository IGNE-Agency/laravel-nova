<?php

declare(strict_types=1);

use Igne\Nova\IgneNova;
use Igne\Nova\Tests\Fixtures\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Nova\Nova;

beforeEach(function (): void {
    config()->set('app.timezone', 'Europe/Amsterdam');
});

it('shows dates in the app timezone without a resolver', function (): void {
    $request = $this->serveNova(User::factory()->make(['timezone' => 'Asia/Tokyo']));

    expect(Nova::resolveUserTimezone($request))->toBe('Europe/Amsterdam');
});

it('shows dates in the timezone the resolver gives for the user', function (): void {
    IgneNova::resolveTimezoneUsing(static fn (Authenticatable $user): ?string => $user instanceof User ? $user->timezone : null);

    $request = $this->serveNova(User::factory()->make(['timezone' => 'Asia/Tokyo']));

    expect(Nova::resolveUserTimezone($request))->toBe('Asia/Tokyo');
});

it('falls back to the app timezone when the resolver gives none', function (): void {
    IgneNova::resolveTimezoneUsing(static fn (): ?string => null);

    $request = $this->serveNova(User::factory()->create());

    expect(Nova::resolveUserTimezone($request))->toBe('Europe/Amsterdam');
});
