<?php

declare(strict_types=1);

use Igne\Nova\Tests\Fixtures\Nova\Article;
use Illuminate\Support\Facades\Lang;

it('takes the labels from the translations keyed by the URI key', function (): void {
    Lang::addLines([
        'nova.resources.articles.plural' => 'Artikelen',
        'nova.resources.articles.singular' => 'Artikel',
    ], 'nl');
    app()->setLocale('nl');

    expect(Article::label())->toBe(__('nova.resources.articles.plural'))
        ->and(Article::singularLabel())->toBe(__('nova.resources.articles.singular'));
});

it('falls back to Nova\'s labels without a translation', function (): void {
    expect(Article::label())->toBe('Articles')
        ->and(Article::singularLabel())->toBe('Article');
});
