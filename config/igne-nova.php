<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| IGNE Nova
|--------------------------------------------------------------------------
|
| The settings of the igne-agency/laravel-nova package. Publish this file
| with "php artisan vendor:publish --tag=igne-nova-config" to change them.
| Nova settings the app makes itself, in config/nova.php or its own
| NovaServiceProvider, win over these.
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Main menu
    |--------------------------------------------------------------------------
    |
    | groups: the app's enum that implements
    | Igne\Nova\Menu\Contracts\NovaMenuGroup. Null finds the only such enum
    | in the discover folders.
    |
    | badge_ttl: Nova rebuilds the menu on every page visit, so the counts of
    | menu badges are cached for this many seconds.
    |
    */

    'menu' => [
        'groups' => null,
        'discover' => [app_path()],
        'badge_ttl' => 300,
    ],

    /*
    |--------------------------------------------------------------------------
    | Panel
    |--------------------------------------------------------------------------
    |
    | Breadcrumbs above every page, the path Nova opens after login (null
    | keeps Nova's: the main dashboard), and the footer with the app's
    | copyright and IGNE as its builder instead of Nova's version.
    |
    */

    'breadcrumbs' => true,

    'initial_path' => null,

    'footer' => true,

    /*
    |--------------------------------------------------------------------------
    | Brand
    |--------------------------------------------------------------------------
    |
    | The logo: the absolute path to an SVG file. The colour: one hex colour,
    | which becomes shade 500 of Nova's primary scale with the other shades
    | mixed from it, or hex colours keyed by shade (50 to 950).
    |
    */

    'brand' => [
        'logo' => null,
        'color' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Assets
    |--------------------------------------------------------------------------
    |
    | The app's own stylesheets and scripts for Nova, as name => absolute
    | path. Lazy images lets the browser load images Nova renders when they
    | scroll into view, instead of all at once.
    |
    */

    'assets' => [
        'styles' => [],
        'scripts' => [],
    ],

    'lazy_images' => true,

];
