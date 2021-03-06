<?php

$autoloadScripts = array(
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../../autoload.php',
);
foreach ($autoloadScripts as $script) {
    if (file_exists($script)) {
        require_once $script;
        break;
    }
}

if (!class_exists('Mike\DependencyContainer')) {
    echo 'Could not autoload classes, please read the readme!' . PHP_EOL;
    exit(1);
}

$container = new Mike\DependencyContainer();
call_user_func($container->getDependencies()->runApplication);
