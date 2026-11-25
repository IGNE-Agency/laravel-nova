# igne-agency/laravel-nova

The [Laravel Nova](https://nova.laravel.com) features every IGNE project needs, in one package. Install it, and the panel is set up the way all our panels are; each feature is opt-in or can be turned off per project.

## Why

Every Nova project rebuilt the same pieces, each slightly differently: a grouped sidebar, a Dutch translation of Nova, brand colours converted to RGB by hand, a guard against sort links that crash the index, enum badges and period filters. This package is those pieces once, tested, and the same in every project.

## Features

| Feature | Default | Turn it on or off |
|---|---|---|
| Main menu grouped by your `NovaMenuGroup` enum, with cached badges | Off | Call `GroupedMenu` from `Nova::mainMenu()`; see [Main menu](docs/menu.md). |
| Breadcrumbs, initial path and the IGNE footer | On | `layout`; see [Layout](docs/layout.md). |
| The user's preferred locale and timezone | On | Laravel's `HasLocalePreference` and `IgneNova::resolveTimezoneUsing()`; see [Localization](docs/localization.md). |
| Logo and brand colour from one hex colour or a full scale | Off | `brand`; see [Branding](docs/branding.md). |
| The app's own Nova stylesheets and scripts | Off | `assets.styles`, `assets.scripts`; see [Assets](docs/assets.md). |
| Lazy loading of the images Nova renders | On | `assets.lazy_images`; see [Assets](docs/assets.md). |

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
- [Layout](docs/layout.md)
- [Localization](docs/localization.md)
- [Assets](docs/assets.md)
- [Branding](docs/branding.md)
- [Development](docs/development.md)
