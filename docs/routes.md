# Routes

The app's own routes on Nova's path, such as a download or an invitation link, go in `routes/admin.php`. The package loads that file when it exists. Publish a starting file with:

```bash
php artisan vendor:publish --tag=igne-nova-routes
```

Register the routes with `Nova::router()`, which puts them on Nova's domain and path, so `/reports/{report}/download` is served at `/nova/reports/{report}/download`. The stub passes `config('nova.api_middleware')`, which requires a login to Nova. A route for guests, such as an invitation link, gets `Nova::router()` without arguments, which uses `config('nova.middleware')`, or its own middleware:

```php
use Laravel\Nova\Nova;

Nova::router(['nova', 'throttle:invitations'])
    ->get('/invitations/{invitationToken}', AcceptInvitationController::class)
    ->name('nova.invitations.accept');
```

Set `routes.file` in `config/igne-nova.php` to another path, relative to the app's base path, to load a different file, or to `null` to load none.

Nova's own routes, such as its login and password reset routes, stay in the app's `NovaServiceProvider`. They can't move to `routes/admin.php`: Laravel skips routes files once routes are cached, and `Nova::routes()->register()` does more than add routes, it also turns on Nova's login.

```php
protected function routes(): void
{
    Nova::routes()
        ->withAuthenticationRoutes()
        ->withPasswordResetRoutes()
        ->register();
}
```
