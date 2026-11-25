<?php

declare(strict_types=1);

use Laravel\Nova\Nova;

// The app's own routes on Nova's path; Nova's own routes stay in NovaServiceProvider::routes().
Nova::router(config('nova.api_middleware'))->group(function (): void {
    //
});
