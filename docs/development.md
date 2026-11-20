# Development

The package runs on its own, on Orchestra Testbench with Nova. Nova is paid, so `composer install` needs Nova credentials in your Composer auth (`composer config -g http-basic.nova.laravel.com <email> <license key>`):

```bash
composer install
composer lint      # Pint
composer analyse   # Larastan, level max on src and tests/Fixtures
composer test      # Pest
```

`tests/TestCase.php` boots Nova with the fixture Nova setup in `tests/Fixtures/Nova` (a main dashboard and a user resource, every user may open Nova), an in-memory SQLite database and the fixture user in `tests/Fixtures`.

## CI

CI (`.github/workflows/ci.yml`) runs Pint, Larastan and Pest on pull requests and pushes to `main`, on the lowest and the newest dependencies the constraints allow. Nova is installed with the `COMPOSER_AUTH` repository secret, a JSON object like `{"http-basic":{"nova.laravel.com":{"username":"…","password":"…"}}}`. Without the secret the jobs pass without checking anything, and say so in a notice.
