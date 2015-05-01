<?php
require '../vendor/autoload.php';

$env = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env';
$adr = (new \Radar\Adr\Factory($env))->newInstance([
    'Example\Config',
]);

$adr->get('Example\Hello', '/{name}?', 'Example\Hello\Domain')
    ->defaults(['name' => 'world']);

$adr();
