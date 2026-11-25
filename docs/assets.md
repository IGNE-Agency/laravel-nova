# Assets

Register the app's own Nova stylesheets and scripts under `assets` in `config/igne-nova.php`, as `name => absolute path`. A path that does not exist fails at boot, so a typo can't go unnoticed:

```php
'assets' => [
    'styles' => ['brand' => resource_path('css/nova.css')],
    'scripts' => [],
    'lazy_images' => true,
],
```

`lazy_images`, on by default, lets the browser load the images Nova renders when they scroll into view, instead of all at once.
