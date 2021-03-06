<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

// Routes
$routes = require __DIR__ . '/../app/routes.php';
$routes($app);

$app->run();
