# Middleware

Radar honors filter-style middleware at three points in the execution path:

- "before", prior to the routing-and-action phase (this is generally
to modify the inbound request in some way)

- "after", once the action is complete but before the response is sent (this is
generally to modify the outbound response in some way)

- "finish", after the response is sent (this is generally for cleanup tasks)

> N.b.: There is no "route-specific" middleware in Radar. All middleware is
> called regardless of the routing and action results. See the "Middleware and
> Domain Activity" section below for the rationale behind this constraint.

A middleware callable must have the following signature:

```php
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\RespponseInterface;

function (ServerRequestInterface $request, ResponseInterface $response) { ... }
```

## Invoking Middleware

To add middleware logic to the execution path, call one of these methods inside
`web/index.php` as appropriate:

- `$adr->before(...)` to add a "before" middleware
- `$adr->after(...)` to add an "after" middleware
- `$adr->finish(...)` to add a "finish" middleware

Pass a class name as the only parameter to the method. The underlying
dependency injection container will create an instance of that class and call its
`__invoke()` method. Alternatively, pass an array of the form
`['ClassName', 'method']`; in this case, the underlying
dependency injection container will create an instance of that class and call
the specified method.

## Request and Response Are Immutable

Remember that the _Request_ and _Response_ objects are immutable. You can work
with them inside your middleware logic, but changes to them **will not** be
honored by anything outside that logic.

To make sure changes to the _Request_ and _Response_ are transmitted throughout
the system, your middleware signature should use *references* to the objects,
like so:

```php
function (ServerRequestInterface &$request, ResponseInterface &$response) { ... }
```

For example:

```php
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\RespponseInterface;

class MyMiddleware
{
    public function __invoke(
        ServerRequestInterface &$request,
        ResponseInterface $response
    ) {
        $request = $request->withAttribute('my-custom-attribute', 'my-value');
    }

}
```

That will cause the `$request` to be modified as if it were shared throughout
the Radar system, not just a local change to an immutable instance.

## Early Exit

Usually, your middleware need return nothing at all. However, if your middleware
returns a _Response_ object, Radar will ignore the rest of the middleware in that
phase and go on to the next phase of the execution path. The returned _Response_
will replace of any existing _Response_ object in Radar. (You can, of course,
return the existing _Response_ object, and that will still cause an early exit
from the current middleware phase.)

If a "before" middleware returns a _Response_ for an early exit, Radar will skip
past the routing, action, and "after" middleware phases, and proceed directly to
sending the _Response_ and the "finish" middleware phase.

Early exit in the "after" stage has the same effect, but the routing and action
will already have occurred at that point. Radar will skip forward to the sending
and "finish" middleware phase.

Finally, early exit in the "finish" phase will have no effect other than skipping
the remaining "finish" middleware elements.

## Middleware And Domain Activity

You are going to be very tempted to place domain-related activity in your
middleware, things like "checking to see if a user is authenticated" and so on.
Resist this temptation. Middleware should only be about inspecting and modifying
the request and response, *not* about handling domain elements. It is not part
of your core application; it is part of the HTTP user interface to that
application.

One shorthand way of determining if you are doing domain work is this: if you
have to touch a storage system of any sort, it's probably domain related. This
includes things like starting a session, which uses some form of storage system
to re-establish itself.

This may seem onerous, but remember: **Radar is an HTTP-specific user interface
wrapper** around your core application. Anything that relates to authentication,
authorization, database, disk, and so on are more properly part of your domain
work, *not* the user- interface work related to the input and output related to
that domain. All the data you need to work with the domain should come from the
incoming request, and all the data you need to build a response should emit from
the domain as a _Payload_.

### Navigation

* Continue to [Environment Variables](/docs/environment.md)
* Back to [Routing](/docs/routing.md)
