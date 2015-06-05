<?php
/**
 * Bootstrapping
 */

// autoloader
require '../vendor/autoload.php';

// environment variables
josegonzalez\Dotenv\Loader::load([
    'filepath' => dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env',
    'toEnv' => true,
]);

// action-domain-responder object
$boot = new Radar\Adr\Boot();
$adr = $boot->adr();

/**
 * Setup
 */

// pipeline middleware
$adr->middle('Radar\Adr\Handler\ExceptionHandler');
$adr->middle('Radar\Adr\Handler\RoutingHandler');
$adr->middle('Radar\Adr\Handler\ActionHandler');
$adr->middle('Radar\Adr\Handler\SendingHandler');

// routes
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
