<?php

declare(strict_types=1);

namespace Igne\Nova\Resources\Concerns;

use Illuminate\Support\Facades\Lang;
use Stringable;

/**
 * Takes a resource's labels from the app's translations, keyed by its URI key:
 *
 *     // lang/nl/nova.php
 *     'resources' => [
 *         'users' => ['plural' => 'Gebruikers', 'singular' => 'Gebruiker'],
 *     ],
 *
 * A missing key falls back to the label Nova generates from the class name.
 * Use it on the app's base Nova resource.
 */
trait TranslatesLabels
{
    public static function label(): Stringable|string
    {
        return static::translatedLabel('plural') ?? parent::label();
    }

    public static function singularLabel(): Stringable|string
    {
        return static::translatedLabel('singular') ?? parent::singularLabel();
    }

    private static function translatedLabel(string $form): ?string
    {
        $key = 'nova.resources.'.static::uriKey().'.'.$form;

        if (! Lang::has($key)) {
            return null;
        }

        $label = __($key);

        return \is_string($label) ? $label : null;
    }
}
