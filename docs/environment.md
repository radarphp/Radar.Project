# Environment Variables

You can place environment-specific values in the `.env` file at the Radar
project root. Radar will use this file at boot time to populate the `$_ENV`
superglobal. You can also use `getenv()` to get the same values.

The Radar boot logic uses the [josegonzalez/dotenv](https://github.com/josegonzalez/php-dotenv) library for the environment loading. You can control the loader behavior by setting options through the _Radar\Adr\Boot_ constructor in `web/index.php`; please consult the [php-dotenv documentation](https://github.com/josegonzalez/php-dotenv#static-environment-definition) for more information.

### Navigation

* Continue to [Container Configuration](/docs/container.md)
* Back to [Middleware](/docs/middleware.md)
