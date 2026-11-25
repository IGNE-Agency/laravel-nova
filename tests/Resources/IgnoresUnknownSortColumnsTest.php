<?php

declare(strict_types=1);

use Igne\Nova\Tests\Fixtures\User;
use Illuminate\Testing\TestResponse;

/**
 * @return list<string>
 */
function articleNames(TestResponse $response): array
{
    return collect($response->json('resources'))
        ->map(static fn (array $resource): string => collect($resource['fields'])->firstWhere('attribute', 'name')['value'])
        ->all();
}

beforeEach(function (): void {
    User::factory()->create(['name' => 'Zoe']);
    User::factory()->create(['name' => 'Anna']);
});

it('ignores a sort on something that is not a column instead of failing the index', function (): void {
    $response = $this->actingAs(User::factory()->create(['name' => 'Mia']))
        ->getJson('/nova-api/articles?orderBy=author&orderByDirection=asc')
        ->assertOk();

    expect(articleNames($response))->toHaveCount(3);
});

it('still sorts on a column', function (): void {
    $response = $this->actingAs(User::factory()->create(['name' => 'Mia']))
        ->getJson('/nova-api/articles?orderBy=name&orderByDirection=asc')
        ->assertOk();

    expect(articleNames($response))->toBe(['Anna', 'Mia', 'Zoe']);
});

it('sorts on a column named with its table', function (): void {
    $response = $this->actingAs(User::factory()->create(['name' => 'Mia']))
        ->getJson('/nova-api/articles?orderBy=users.name&orderByDirection=desc')
        ->assertOk();

    expect(articleNames($response))->toBe(['Zoe', 'Mia', 'Anna']);
});
