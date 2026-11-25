<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| IGNE Nova
|--------------------------------------------------------------------------
|
| The settings of the igne-agency/laravel-nova package. Publish this file
| with "php artisan vendor:publish --tag=igne-nova-config" to change them.
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

];
