# Localization

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
