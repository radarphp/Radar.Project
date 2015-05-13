# Routing

Before continuing, remove the default `Hello` route from the `web/index.php`
file. It looks like this:

```php
$adr->get('Hello', '/{name}?', function (array $input) {
        $payload = new \Aura\Payload\Payload();
        return $payload
            ->setStatus($payload::SUCCESS)
            ->setOutput([
                'phrase' => 'Hello ' . $input['name']
            ]);
    })
    ->defaults(['name' => 'world']);
```

That will prepare your project for real use.

## Adding A Route

The Radar routing sysyem is based on
[Aura.Router](https://github.com/auraphp/Aura.Router), with some details
modified specifically for Radar.

Let's add an HTTP PATCH route. In `web/index.php`, call `$adr->patch()` with a
route name and URL path, and a _Domain_ specification.

```php
$adr->patch('Todo\EditItem', '/todo/{id}', 'Domain\Todo\ApplicationService\EditItem');
```

The route name doubles as a class name prefix for optional _Input_ and
_Responder_ classes. We will talk more about that later.

The path is typical for routing systems, using placeholder tokens for route
attribute values.

The _Domain_ specification is a string or an array:

- If a string, Radar will instantiate this class using the internal dependency
injection container and call its `__invoke()` method with the user input from
the HTTP request.

- If an array in the format `['ClassName', 'method']`, the dependency injection
container will instantiate the specfied class name, and then call the specified
method with the user input from the HTTP request.

## Specifying An Input Class

TBD.

## Specifying A Responder Class

TBD.

## Other Route Specifications
