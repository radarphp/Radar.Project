<?php
namespace Example;

use Aura\Di\Container;
use Aura\Di\ContainerConfig;

class Config extends ContainerConfig
{
    public function define(Container $di)
    {
        $di->params['Example\Hello\Domain']['payload'] = $di->lazyNew('Aura\Payload\Payload');
    }

    public function modify(Container $di)
    {
    }
}
