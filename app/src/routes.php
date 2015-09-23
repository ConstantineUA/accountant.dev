<?php

// Index action
$app->get('/', 'accountant.controller.statistics:indexAction')
    ->bind('homepage');

// Statistics Controller
$app->get('/statistics/start/{start}/end/{end}/', 'accountant.controller.statistics:byCategoriesAction')
    ->convert('start', 'Accountant\Controller\AbstractController::convertUrlStartDate')
    ->convert('end', 'Accountant\Controller\AbstractController::convertUrlEndDate')
    ->bind('statisticsByDate');

$app->get('/statistics/category/{id}/payments/start/{start}/end/{end}/', 'accountant.controller.statistics:singleCategoryAction')
    ->convert('start', 'Accountant\Controller\AbstractController::convertUrlStartDate')
    ->convert('end', 'Accountant\Controller\AbstractController::convertUrlEndDate')
    ->bind('statisticsByCategory');

$app->get('/statistics/custom/', 'accountant.controller.statistics:customAction')
    ->bind('statisticsCustom');

// Category Controller
$app->get('/category/', 'accountant.controller.category:indexAction')
    ->bind('categoryIndex');

$app->match('/category/add/', 'accountant.controller.category:addAction')
    ->bind('categoryAdd');

// Payment Controller
$app->match('/payment/add/', 'accountant.controller.payment:addAction')
    ->bind('paymentAdd');
