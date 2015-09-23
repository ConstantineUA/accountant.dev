<?php


return array(
    'isDevMode' => true,

    'pathProject' => __DIR__ . '/../../',
    'pathCache' => __DIR__ . '/../cache/',
    'pathSrc' => __DIR__ . '/../src/',
    'pathViews' => __DIR__ . '/../views/',
    'pathLocales' => __DIR__ . '/../locales/',

    'routesFile' => __DIR__ . '/../src/routes.php',

    'dbParams' => array(
        'driver' => 'pdo_mysql',
        'user' => 'accountant',
        'password' => 'accountant',
        'dbname' => 'accountant'
    ),

    'outputDateFormatGeneral' => 'M jS Y',
    'outputDateFormatShort' => 'M jS',
);