<?php
namespace Radar\Adr;

require '../vendor/autoload.php';

$boot = new Boot(dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env');
$adr = $boot([
    'Example\Config'
]);

$adr->get('Example\Hello', '/{name}?', 'Example\Hello\Domain')
    ->defaults(['name' => 'world']);

$adr();
