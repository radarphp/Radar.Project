# Alternative Handlers

Radar comes with four handlers to deal with the execution of core tasks, all in
the _Radar\Adr\Handler_ namespace:

- _RoutingHandler_ to pick a route for the incoming request;
- _ActionHandler_ to execute the action mapped to the route, invoking the _Input_, _Domain_, and _Responder_;
- _SendingHandler_ to send the response; and
- _ExceptionHandler_ to handle any exceptions that bubble up to Radar.

In general, you should not have to override these handlers, but it is
possible.To override a handler and replace it with your own, call the
appropriate method on the `$adr` object in `web/index.php`:

- `$adr->routingHandler('MyRoutingHandler')` to set the class name for the routing handler;

- `$adr->actionHandler('MyActionHandler')` to set the class name for the action handler;

- `$adr->sendingHandler('MySendingHandler')` to set the class name for the sending handler; and

- `$adr->exceptionHandler('MyExceptionHandler')` to set the class name for the exception handler.

The internal dependency injection container will create an instance of the
specified class and `__invoke()` it at the appropriate time.
