<?php

declare(strict_types=1);

use Igne\Nova\Tests\Fixtures\User;

it('merges the package config', function (): void {
    expect(config('igne-nova'))->toBeArray();
});

it('serves Nova with the package installed', function (): void {
    $this->actingAs(User::factory()->create())
        ->get('/nova/dashboards/main')
        ->assertOk();
});
