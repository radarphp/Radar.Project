# Middleware

Radar uses the [Pipeline](https://github.com/pipeline/Pipeline.Pipeline) system for dispatching middleware. You can read more about middleware there.

To add Pipeline-compatible middleware logic to the execution path, call the
`$adr->middle()` method in `web/index.php`. Pass a class name as the only
parameter to the method.The underlying dependency injection container will
create an instance of that class and call its `__invoke()` method.

```php
$adr->middle('My\Middleware\Handler');
```

Alternatively, pass an array of the form `['ClassName', 'method']`. In this
case, the underlying dependency injection container will create an instance of
that class and call the specified method.

## Middleware Exceptions

If middleware in the pipeline fails to catch an exception, the default
_Radar\Adr\Handler\ExceptionHandler_ will catch it automatically. The
_ExceptionHandler_ will:

- write the _Exception_ message to the _Response_ body,
- set a `500` HTTP status code,
- immediately send the _Response_ using the _Radar\Adr\Sender_, and
- return the sent _Response_ to the previous middleware handler.

The _ExceptionHandler_ should be the first middleware in the pipeline.


## Middleware And Domain Activity

You are going to be very tempted to place domain-related activity in your
middleware, things like "checking to see if a user is authenticated" and so on.
Resist this temptation. Middleware should only be about inspecting and modifying
the request and response, *not* about handling domain elements. Middleware is
not part of your core application; it is part of the HTTP user interface to that
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
