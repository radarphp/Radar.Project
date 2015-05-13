# Getting Started

## Project Structure

A Radar project looks like this:

    project/            # The project directory
    ├── .env            # Environment config file
    ├── composer.json   # Project composer file
    ├── src/            # Project class files
    │   ├── ...
    │   ├── ...
    │   └── ...
    ├── vendor/         # Composer-loaded packages
    │   ├── ...
    │   ├── ...
    │   └── ...
    └── web/            # Web document root
        └── index.php   # Bootstrap script


The `index.php` file is where you will:

- Load custom package configurations
- Define routes, each with optional callables for input, domain, and responder
- Specify "before", "after", and "finish" middleware callables
- Add custom handlers

Adding a route in `web/index.php` takes three pieces of information:

1. a route name, which doubles as a class name prefix for optional _Input_
and _Responder_ classes,

2. a path with optional placeholder tokens, and

3. an optional callable _Domain_ class or closure.

For example, in `web/index.php`:

    $adr->get('Example\Foo', '/foo/{bar}', 'Example\Foo\Domain')

Then add, in a `src/Foo` directory:

```php
<?php
namespace Example\Foo;

use Aura\Payload\Payload;

class Domain
{
    public function __invoke(array $input)
    {
        $payload = new Payload();
        return $payload
            ->setStatus(Payload::FOUND)
            ->setOutput([
                'bar' => $input['bar']
            ]);
    }
}
?>
```

Then browse to <http://localhost:8080/foo/zim> and see JSON output.

* * *

### Execution Path

The full execution process looks like this:

    Boot -> Setup -> Run -> Before [-> Route -> Action -> After] -> Send -> Finish

Point by point:

- A bootstrap phase to build a DI container with custom configuration;

- The setup phase (this is where you define the URL routes with their action
elements, add middleware callables, define custom Action/Routing/Exception/
Sending handlers), etc.

- The run phase:

    - All "before" middleware callables are invoked in order; this is where the _Request_
    is modified as needed. (If a "before" middleware returns a _Response_, that
    skips the process ahead to the "send" phase.)

    - The "routing" phase to determine the _Route_ based on the _Request_.

    - The "action" phase to use the _Route_ for:

        - An _Input_ callable (either a class or closure) is invoked to examine the
        incoming HTTP _ServerRequest_ message and extract values to pass along to the
        core _Domain_ callable.

        - A _Domain_ callable is invoked using those values, and a _Payload_ or other
        output from the _Domain_ is received in return.

        - A _Responder_ callable is invoked with the _Domain_ output; the _Responder_
        then builds the outgoing HTTP _Response_ message.

    - All "after" middleware callables are invoked

    - The "sending" phase, where _Response_ is sent back

    - All "finish" middleware callables are invoked

