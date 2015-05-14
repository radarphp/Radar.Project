# Container Configuration

The Radar dependency injection container is an instance of
[Aura.Di](https://github.com/auraphp/Aura.Di). The Radar boot process
runs a list of container configuration classes when building the container.
You can learn about container configuration classes
[here](https://github.com/auraphp/Aura.Di/blob/3.x/docs/index.md#container-builder-and-config-classes).

To tell the boot process which container configuration classes to load, and in
which order, pass an array of config class names to the `boot()` call in
`web/index.php`.

```php
$boot = new Boot(dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env');
$adr = $boot([
    'Foo\Bar\ContainerConfig',
    'Baz\Dib\_Config',
    'My\Other\ConfigClass',
]);
```

These will be loaded by the container builder in order.

Of special importance, if your _Domain_ is in a separate package and has its
own configuration class, be sure to include that class name in the list.
