<?php

declare(strict_types=1);

use DonorDocs\Controllers\DashboardController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Views\Twig;

return static function (App $app, array $container): void {
    $app->get('/', function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
        /** @var Twig $view */
        $view = $container['view'];
        /** @var LoggerInterface $logger */
        $logger = $container['logger'];

        $controller = new DashboardController($view, $logger);

        return $controller($request, $response);
    })->setName('dashboard');
};
