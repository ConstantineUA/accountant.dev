<?php


require __DIR__ . '/../vendor/autoload.php';

ini_set('display_errors', 'On');
error_reporting(-1);
date_default_timezone_set('Europe/Kiev');


$app = new Accountant\Application(
    require __DIR__ . '/../app/config/dev.php'
);

require $app['config']['routesFile'];

$app->run();
