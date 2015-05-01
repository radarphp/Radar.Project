# Radar

**WARNING: This project is under heavy development.**

Radar is an [Action-Domain-Responder](http://pmjones.io/adr) foundation for PHP.

To install:

    composer create-project -s dev radar/project project-name
    cd project-name
    cp _env .env
    php -S localhost:8080 -t web/

You can then browse to <http://localhost:8080/> and see JSON output.

Browse to <http://localhost:8080/your-name> and see modified JSON output.

Adding a route in `web/index.php` takes three pieces of information:

1. a route name, which doubles as a class name prefix for optional _Input_
and _Responder_ classes,

2. a path with optional placeholder tokens, and

3. a callable _Domain_ class.

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
