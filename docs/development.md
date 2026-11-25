# Development

The package runs on its own, on Orchestra Testbench with Nova. Nova is paid, so `composer install` needs Nova credentials in your Composer auth (`composer config -g http-basic.nova.laravel.com <email> <license key>`):

```bash
composer install
composer lint      # Pint
composer analyse   # Larastan, level max on src and tests/Fixtures
composer test      # Pest
```

`tests/TestCase.php` boots Nova with the fixture Nova setup in `tests/Fixtures/Nova` (a main dashboard and a user resource, every user may open Nova), an in-memory SQLite database and the fixture user in `tests/Fixtures`.

## JavaScript

The 403 page is a Vue component built with Nova's own setup: `laravel/nova-devtool` (a Composer dev dependency) and Laravel Mix, configured in `webpack.mix.js`. The build goes to `dist/`, which is committed, so apps that install the package need no Node. After changing a `.vue` file:

```bash
npm install
npm run prod   # or npm run watch while working on it
```

Commit `dist/` with the change. Vue and Nova stay globals in the build, so a Nova update needs no rebuild.

## CI

CI (`.github/workflows/ci.yml`) runs Pint, Larastan and Pest on pull requests and pushes to `main`, on the lowest and the newest dependencies the constraints allow. Nova is installed with the `COMPOSER_AUTH` repository secret, a JSON object like `{"http-basic":{"nova.laravel.com":{"username":"…","password":"…"}}}`. Without the secret the jobs pass without checking anything, and say so in a notice.
