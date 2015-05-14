# Execution Process

The full execution process in Radar looks like this:

    Boot -> Setup -> Run -> Before [-> Routing -> Action -> After] -> Send -> Finish

Point by point:

- A bootstrap phase to build a DI container with custom configuration;

- The setup phase (this is where you define the URL routes with their action
elements, add middleware callables, define custom Action/Routing/Exception/
Sending handlers), etc.

- The run phase:

    - All "before" middleware callables are invoked. If a "before"
    middleware returns a _Response_, that skips the process ahead to the
    "send" phase.)

    - The "routing" phase to determine the _Route_ based on the _Request_.

    - The "action" phase to use the _Route_ for:

        - An _Input_ callable (either a class or closure) is invoked to
        examine the incoming HTTP _ServerRequest_ message and extract values
        to pass along to the core _Domain_ callable.

        - A _Domain_ callable is invoked using those values, and a _Payload_
        from the _Domain_ is received in return.

        - A _Responder_ callable is invoked with the _Domain_ output; the
        _Responder_ then builds the outgoing HTTP _Response_ message.

    - All "after" middleware callables are invoked

    - The "sending" phase, where _Response_ is sent back

    - All "finish" middleware callables are invoked

