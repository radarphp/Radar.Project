<?php
/**
 * Bootstrapping
 */
require '../vendor/autoload.php';
$boot = new Radar\Adr\Boot([
    'filepath' => dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env',
    'toEnv' => true,
    'toServer' => true,
    'putenv' => true,
]);
$adr = $boot->adr();

/**
 * Middleware handlers
 */
$adr->middle('Radar\Adr\Handler\RoutingHandler');
$adr->middle('Radar\Adr\Handler\ActionHandler');
$adr->middle('Radar\Adr\Handler\SendingHandler');

/**
 * Routes
 */
$adr->get('Hello', '/{name}?', function (array $input) {
        $payload = new Aura\Payload\Payload();
        return $payload
            ->setStatus($payload::SUCCESS)
            ->setOutput([
                'phrase' => 'Hello ' . $input['name']
            ]);
    })
    ->defaults(['name' => 'world']);

/**
 * Run
 */
$adr->run(
    Zend\Diactoros\ServerRequestFactory::fromGlobals(),
    new Zend\Diactoros\Response()
);
