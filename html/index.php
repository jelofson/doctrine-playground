<?php
session_start();

require '../vendor/autoload.php';

use Slim\Factory\AppFactory;
use League\Container\Container;

// Include the settings file
$settings = require '../src/Blog/settings.php';

// Get the container
$container = new Container;

AppFactory::setContainer($container);
$app = AppFactory::create();
$app->setBasePath($settings['base_uri']);

$callableResolver = $app->getCallableResolver();
$responseFactory = $app->getResponseFactory();



$routeParser = $app->getRouteCollector()->getRouteParser();
$container->add('router', $routeParser);

// Get dependencies
include '../src/Blog/dependencies.php';



// include project routes
include '../src/Blog/routes.php';

$errorMiddleware = $app->addErrorMiddleware(
    true, true, true 
);

// Run app
$app->run();
