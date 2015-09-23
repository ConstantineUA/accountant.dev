<?php


require __DIR__ . '/../vendor/autoload.php';

$app = new Accountant\Application(
    require __DIR__ . '/../app/config/dev.php'
);

require $app['config']['routesFile'];

$app->run();
