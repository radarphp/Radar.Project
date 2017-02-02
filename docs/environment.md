# Environment Variables

You can place environment-specific values in the `.env` file at the Radar
project root. Radar will use this file at boot time to populate the `$_ENV`
superglobal. You can also use `getenv()` to get the same values.

The `public/index.php` file uses the [josegonzalez/dotenv](https://github.com/josegonzalez/php-dotenv) library for the environment loading. You can control the loader behavior by setting options through the `Loader::load()` call in `public/index.php`. Please consult the [php-dotenv documentation](https://github.com/josegonzalez/php-dotenv#static-environment-definition) for more information.

Alternatively, if you have another loader you prefer to use, or none at all,
modify `public/index.php` and `composer.json` as you see fit.
