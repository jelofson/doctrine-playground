<?php
require 'vendor/autoload.php';
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use League\Container\Container;

$container = new Container();

// replace this with the path to your own project bootstrap file.
$settings = include 'src/Blog/settings.php';
require_once 'src/Blog/dependencies.php';

// replace with mechanism to retrieve EntityManager in your app
$entityManager = $container->get('entityManager');

return ConsoleRunner::createHelperSet($entityManager);