<?php

declare(strict_types=1);

use Igne\Nova\Tests\Fixtures\User;

it('serves Nova in the user\'s preferred locale', function (): void {
    $this->serveNova(User::factory()->make(['locale' => 'nl']));

    expect(app()->getLocale())->toBe('nl');
});

it('keeps the app locale for a user without a preference', function (): void {
    $this->serveNova(User::factory()->make(['locale' => null]));

    expect(app()->getLocale())->toBe('en');
});

it('keeps the app locale for a guest', function (): void {
    $this->serveNova();

    expect(app()->getLocale())->toBe('en');
});
