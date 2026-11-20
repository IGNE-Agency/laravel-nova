# igne-agency/laravel-nova

The [Laravel Nova](https://nova.laravel.com) features every IGNE project needs, in one package. Install it, and the panel is set up the way all our panels are; each feature is opt-in or can be turned off per project.

## Why

Every Nova project rebuilt the same pieces, each slightly differently: a grouped sidebar, a Dutch translation of Nova, brand colours converted to RGB by hand, a guard against sort links that crash the index, enum badges and period filters. This package is those pieces once, tested, and the same in every project.

## Features

| Feature | Default | Turn it on or off |
|---|---|---|
| Main menu grouped by your group enum, with cached badges | Off | Call `GroupedMenu` from `Nova::mainMenu()`; see [Main menu](docs/menu.md). |
| Breadcrumbs, initial path, IGNE footer, lazy images | On | `breadcrumbs`, `initial_path`, `footer`, `lazy_images`; see [Panel defaults](docs/panel.md). |
| The user's preferred locale and timezone | On | Laravel's `HasLocalePreference` and `IgneNova::resolveTimezoneUsing()`; see [Panel defaults](docs/panel.md). |
| Logo and brand colour from one hex colour or a full scale | Off | `brand`; see [Branding](docs/branding.md). |
| The app's own Nova stylesheets and scripts | Off | `assets`; see [Panel defaults](docs/panel.md#assets). |
| Ignore sorts on unknown columns instead of a SQL error | Off | `IgnoresUnknownSortColumns` on the base resource; see [Resource helpers](docs/resources.md). |
| Resource labels from translations keyed by URI key | Off | `TranslatesLabels` on the base resource; see [Resource helpers](docs/resources.md). |
| Badge field driven by an enum's labels and colours | Off | `EnumBadge::make()`; see [Fields and filters](docs/fields-and-filters.md). |
| Filter on a timestamp within the last days | Off | `new CreatedWithin`; see [Fields and filters](docs/fields-and-filters.md#createdwithin). |
| Nova in Dutch, overridable per key by the app | On | The app's `lang/vendor/nova/nl.json` wins; see [Translations](docs/translations.md). |
| A 403 page with "Go back" and "Log out" | On | See [Error pages](docs/error-pages.md). |

## Requirements

- PHP 8.4
- Laravel 13
- Laravel Nova 5.11 or later

## Install

```bash
composer require igne-agency/laravel-nova
```

Publish the config to change the defaults, and the translations to change the package's texts:

```bash
php artisan vendor:publish --tag=igne-nova-config
php artisan vendor:publish --tag=igne-nova-lang
```

## Documentation

- [Main menu](docs/menu.md)
- [Panel defaults](docs/panel.md)
- [Branding](docs/branding.md)
- [Resource helpers](docs/resources.md)
- [Fields and filters](docs/fields-and-filters.md)
- [Translations](docs/translations.md)
- [Error pages](docs/error-pages.md)
- [Development](docs/development.md)
