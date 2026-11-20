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
    | Nova rebuilds the menu on every page visit, so the counts of menu
    | badges are cached for this many seconds.
    |
    */

    'menu' => [
        'badge_ttl' => 300,
    ],

];
