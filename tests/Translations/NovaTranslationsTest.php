<?php

declare(strict_types=1);

use Igne\Nova\Tests\Fixtures\User;
use Illuminate\Support\Facades\File;
use Laravel\Nova\Nova;

/**
 * @return array<string, string>
 */
function packageTranslations(string $locale): array
{
    return json_decode((string) file_get_contents(dirname(__DIR__, 2)."/lang/nova/{$locale}.json"), true);
}

afterEach(function (): void {
    File::delete(lang_path('vendor/nova/nl.json'));
});

it('translates every text of the installed Nova version into Dutch', function (): void {
    $novaKeys = array_keys(json_decode((string) file_get_contents(dirname(__DIR__, 2).'/vendor/laravel/nova/resources/lang/en.json'), true));

    expect(array_diff($novaKeys, array_keys(packageTranslations('nl'))))->toBe([]);
})->skip(! is_file(dirname(__DIR__, 2).'/vendor/laravel/nova/resources/lang/en.json'), 'Nova\'s English translations are not installed.');

it('gives Nova\'s screens the Dutch translations for a Dutch user', function (): void {
    $this->serveNova(User::factory()->make(['locale' => 'nl']));

    expect(Nova::allTranslations()['Go Home'])->toBe(packageTranslations('nl')['Go Home']);
});

it('lets the app\'s own Nova translations win over the package\'s', function (): void {
    File::ensureDirectoryExists(lang_path('vendor/nova'));
    File::put(lang_path('vendor/nova/nl.json'), json_encode(['Go Home' => 'Naar huis']));

    $this->serveNova(User::factory()->make(['locale' => 'nl']));

    expect(Nova::allTranslations()['Go Home'])->toBe('Naar huis')
        ->and(Nova::allTranslations()['Go back'])->toBe(packageTranslations('nl')['Go back']);
});

it('translates Nova\'s server-side texts', function (): void {
    app()->setLocale('nl');

    expect(__('Go back'))->toBe(packageTranslations('nl')['Go back']);
});

it('registers the 403 page', function (): void {
    expect(array_map(static fn ($script): string => $script->name(), Nova::$scripts))->toContain('igne-nova-error-403');
});
