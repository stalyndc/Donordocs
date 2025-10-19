<?php

declare(strict_types=1);

$bootstrap = require __DIR__ . '/../bootstrap/app.php';

$app = $bootstrap['app'];
$container = $bootstrap['container'];

($routes = require __DIR__ . '/../config/routes.php')($app, $container);

$app->run();
