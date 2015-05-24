# Middleware

Radar uses chain- or wrapper-style middleware for all _ServerRequest_ and
_Response_ processing. A middleware callable must have the following signature:

```php
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\RespponseInterface;

function (
    ServerRequestInterface $request, // the incoming request
    ResponseInterface $response,     // the outgoing response
    callable $next                   // the next middleware handler
) {
    // ...
}
```

> N.b.: There is no "route-specific" middleware in Radar. All middleware is
> called regardless of the routing and action results. See the "Middleware and
> Domain Activity" section below for the rationale behind this constraint.

## Invoking Middleware

To add middleware logic to the execution path, call the `$adr->middle()` method
in `web/index.php`. Pass a class name as the only parameter to the method.The
underlying dependency injection container will create an instance of that class
and call its `__invoke()` method.

```php
$adr->middle('My\Middleware\Handler');
```

 Alternatively, pass an array of the form `['ClassName', 'method']`. Un this
case, the underlying dependency injection container will create an instance of
that class and call the specified method.

## Middleware Logic

Your middleware logic should follow this pattern:

- Receive the incoming _ServerRequest_ and _Response_ objects from the previous
  handler as parameters, along with the next handler as a callable.

- Optionally modify the received _ServerRequest_ and _Response_ as desired.

- Optionally invoke the next handler with the _ServerRequest_ and
  _Response_, receiving a new _Response_ in return.

- Optionally returned _Response_ as desired.

- Return the _Response_ to the previous handler.

Here is a skeleton example; your own middleware may or may not perform the
various optional processes:

```php
namespace My\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\RespponseInterface;

class Handler
{
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ) {
        // optionally modify the incoming request
        $request = $request->...;

        // optionally skip the $next handler and return early
        if (...) {
            return $response;
        }

        // optionally invoke the $next handler and get back a new Response
        $response = $next($request, $response);

        // optionally modify the Response if desired
        $response = $response->...;

        // NOT OPTIONAL: return the Response to the previous handler
        return $response;
    }
}
```

> N.b.: You should **always** return the _Response_ from your middleware logic.

Note that this logic chain means the _ServerRequest_ and _Response_ are
subjected to two passes through each middleware handler: once on the way "in"
through each handler via the `$next` invocation, and again on their way "out"
from each handler via the `return` to the previous handler.

For example, if the middleware queue looks like this:

```
$adr->middle('FooHandler');
$adr->middle('BarHandler');
$adr->middle('BazHandler');
```

... the _ServerRequest_ and _Response_ path through the handlers will look like
this:

```
FooHandler is 1st on the way in
    BarHandler is 2nd on the way in
        BazHandler is 3rd on the way in, and 1st on the way out
    BarHandler is 2nd on the way out
FooHandler is 3rd on the way out
```

You can use this dual-pass logic in clever and perhaps unintuitive ways. For
example, a middlware handler placed at the very start may do nothing with
the _ServerRequest_ and call `$next` right away, but it is the handler with
the "real" last opportunity to modify the _Response_.

## Middleware Exceptions

If your middleware logic fails to catch an exception, the default
_Radar\Adr\Handler\ExceptionHandler_ will catch it automatically. The
default _ExceptionHandler_ will:

- append the _Exception_ message to the existing _Response_ body,
- set a `500` HTTP status code,
- immediately send the _Response_ using the _Radar\Adr\Sender_, and
- return the sent _Response_ to the previous middleware handler.

This interrupts the execution of any `$next` middleware and starts the `return`
pass through the previous middleware handlers.

You can set an exception handler of your own by calling
`$adr->exceptionHandler()` and passing a string class name, or an array of
string class name and string method name.

```
$adr->exceptionHandler('My\ExceptionHandler');
```

The exception handler must match this signature:

```php
use Exception;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\RespponseInterface;

function (
    ServerRequestInterface $request, // the incoming request
    ResponseInterface $response,     // the outgoing response
    Exception $exception             // the exception
) {
    // ...
}
```

This is no opportunity to continue to a `$next` middleware handler. If doing so
is important to you, be diligent and catch exceptions yourself inside your
middleware logic.

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
