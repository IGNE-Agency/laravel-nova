# igne-agency/laravel-nova

The [Laravel Nova](https://nova.laravel.com) features every IGNE project needs, in one package. Install it, and the panel is set up the way all our panels are; each feature is opt-in or can be turned off per project.

## Why

Every Nova project rebuilt the same pieces, each slightly differently: a grouped sidebar, a Dutch translation of Nova, brand colours converted to RGB by hand, a guard against sort links that crash the index, enum badges and period filters. This package is those pieces once, tested, and the same in every project.

## Features

| Feature | Default | Turn it on or off |
|---|---|---|
| Main menu grouped by your `NovaMenuGroup` enum, with cached badges | Off | Call `GroupedMenu` from `Nova::mainMenu()`; see [Main menu](docs/menu.md). |

## Requirements

- PHP 8.4
- Laravel 13
- Laravel Nova 5.11 or later

## Install

```bash
composer require igne-agency/laravel-nova
```

Publish the config to change the defaults:

```bash
php artisan vendor:publish --tag=igne-nova-config
```

## Documentation

- [Main menu](docs/menu.md)
- [Development](docs/development.md)
