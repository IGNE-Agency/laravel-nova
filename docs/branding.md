# Branding

Set the logo and the brand colour in `config/igne-nova.php`. The package fills Nova's own `nova.brand` settings with them, unless the app's `config/nova.php` sets them already.

```php
'brand' => [
    'logo' => resource_path('img/logo.svg'), // the absolute path to an SVG file
    'color' => '#18b69b',
],
```

Nova colours its buttons, links and highlights with eleven shades of the primary colour, 50 to 950. One hex colour becomes shade 500, and the package mixes the other shades from it toward white and black, so the panel has no shades left in Nova's default blue.

When a designer delivers the full scale, give it as hex colours keyed by shade; shades you leave out keep Nova's default:

```php
'color' => [
    50 => '#effefa',
    100 => '#c7fff1',
    // …
    950 => '#022c2a',
],
```

A colour that is not a six-digit hex colour fails at boot.
