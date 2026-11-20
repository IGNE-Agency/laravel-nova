# Panel defaults

The package sets up the panel the way all IGNE panels are. Each setting is in `config/igne-nova.php`, and anything the app sets itself in its `NovaServiceProvider` wins, because the app's provider boots after the package's.

| Setting | Default | Config |
|---|---|---|
| Breadcrumbs above every page | On | `breadcrumbs` |
| The path Nova opens after login | Nova's: the main dashboard | `initial_path` |
| Footer: `© {year} {app.name} · Built by IGNE` instead of Nova's version and copyright | On | `footer` |
| Lazy loading of the images Nova renders | On | `lazy_images` |

## Locale

Nova is served in the user's preferred locale: the package uses Laravel's own `Illuminate\Contracts\Translation\HasLocalePreference` contract, the one Laravel also sends notifications in. A user without one, or with an empty locale, gets the app's locale.

```php
class User extends Authenticatable implements HasLocalePreference
{
    public function preferredLocale(): ?string
    {
        return $this->locale;
    }
}
```

## Timezone

Nova shows dates in the app's timezone, `config('app.timezone')`. To show them in each user's own timezone, give the package a resolver; a `null` result falls back to the app's timezone:

```php
use Igne\Nova\IgneNova;

IgneNova::resolveTimezoneUsing(fn (Authenticatable $user): ?string => $user->timezone);
```

## Assets

Register the app's own Nova stylesheets and scripts as `name => absolute path`. A path that does not exist fails at boot, so a typo can't go unnoticed:

```php
'assets' => [
    'styles' => ['brand' => resource_path('css/nova.css')],
    'scripts' => [],
],
```
