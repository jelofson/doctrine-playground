<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use League\Plates\Engine as View;
use Vespula\Form\Form;
use Vespula\Form\Builder\Builder as FormBuilder;

$container->defaultToShared();

$container->add('settings', function () use ($settings) {
    return $settings;
});

$container->add('view', function () use ($container) {
    $settings = $container->get('settings');
    
    $title = $settings['title'];
    $base_uri = $settings['base_uri'];

    $views = dirname(__FILE__) . '/View';
    $layouts = dirname(__FILE__) . '/Layout';

    $view = new View($views);
    $view->addFolder('layouts', $layouts);

    // Note: router was added to container in the html/index.php bootstrap
    $view->addData([
        'title'=>$title,
        'base_uri'=>$base_uri,
        'router'=>$container->get('router'),
    ]);

    return $view;
});


$container->add('entityManager', function () use ($container) {
    
    $settings = $container->get('settings');

    $conn = [
        'driver'   => $settings['db']['driver'],
        'user'     => $settings['db']['user'],
        'password' => $settings['db']['password'],
        'dbname'   => $settings['db']['dbname'],
    ];
    
    $config = Setup::createConfiguration(true, null, null);
    $phpDriver = new \Doctrine\Persistence\Mapping\Driver\StaticPHPDriver(__DIR__ . '/Entity');
    $namespaces = array(
        __DIR__ . '/Entity/xml' => 'Employee\Entity'
    );
    $simplexmlDriver = new \Doctrine\ORM\Mapping\Driver\SimplifiedXmlDriver($namespaces);
    $xmlDriver = new \Doctrine\ORM\Mapping\Driver\XmlDriver([__DIR__ . '/Entity/xml']);
    
    $config->setMetadataDriverImpl($phpDriver);


    $entityManager = EntityManager::create($conn, $config);
    return $entityManager;

});

$container->add('pdo', function () use ($container) {
    $settings = $container->get('settings');
    $db = $settings['db'];
    $dsn = $db['pdo_dsn'];
    $user = $db['user'];
    $pass = $db['password'];

    $pdo = new \PDO($dsn, $user, $pass);
    return $pdo;
    
});

$container->add('formBuilder', FormBuilder::class)
    ->addArgument('pdo')
    ->addMethodCall('setDefaultClasses', [
        $settings['form']['default_classes']
    ])
    ->addMethodCall('setBlacklist', [
        $settings['form']['blacklist']
    ]
);

$container->add('form', Form::class)
    ->addMethodCall('setBuilder', ['formBuilder'])
    ->addMethodCall('autoLf');
