<?php
namespace Radar\Adr;

use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response;

require '../vendor/autoload.php';

// boot
$boot = new Boot(dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env');
$adr = $boot->adr();

// middleware
$adr->middle('Radar\Adr\Handler\RoutingHandler');
$adr->middle('Radar\Adr\Handler\ActionHandler');
$adr->middle('Radar\Adr\Handler\SendingHandler');

// routing
$adr->get('Hello', '/{name}?', function (array $input) {
        $payload = new \Aura\Payload\Payload();
        return $payload
            ->setStatus($payload::SUCCESS)
            ->setOutput([
                'phrase' => 'Hello ' . $input['name']
            ]);
    })
    ->defaults(['name' => 'world']);

// run
$adr->run(
    ServerRequestFactory::fromGlobals(),
    new Response()
);
