# Execution Process

The full execution process in Radar looks like this:

    Boot -> Setup -> Run -> Middleware

Point by point:

- A bootstrap phase to build a DI container with custom configuration;

- The setup phase (this is where you define the URL routes with their action
elements, add middleware callables, define custom Action/Routing/Exception/
Sending handlers), etc.

- The run phase, which executes all middleware callables in turn.

The initial installation middleware queue runs three handlers:

- a _RoutingHandler to determine the _Route_ based on the _ServerRequest_,

- an _ActionHandler_ to use the _Route_ for the action-domain-responder activity:

    - An _Input_ callable is invoked to examine the incoming HTTP
    _ServerRequest_ message and extract values to pass along to the core
    _Domain_ callable.

    - A _Domain_ callable is invoked using those values, and a _Payload_
    from the _Domain_ is received in return.

    - A _Responder_ callable is invoked with the _Domain_ output; the
    _Responder_ then builds the outgoing HTTP _Response_ message.

- a _SendingHandler_ to send the _Response_

You can prepend, append, or replace these handlers with your own middleware.

### Navigation

* Back to [Container Configuration](/docs/container.md)
* Up to [Index](/docs/index.md)
