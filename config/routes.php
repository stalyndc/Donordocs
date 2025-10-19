<?php

declare(strict_types=1);

use DonorDocs\Controllers\DashboardController;
use DonorDocs\Controllers\MarketingController;
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

        $controller = new MarketingController($view, $logger);

        return $controller->home($request, $response);
    })->setName('marketing.home');

    $app->get('/pricing', function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
        /** @var Twig $view */
        $view = $container['view'];
        /** @var LoggerInterface $logger */
        $logger = $container['logger'];

        $controller = new MarketingController($view, $logger);

        return $controller->pricing($request, $response);
    })->setName('marketing.pricing');

    $app->get('/dashboard', function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
        /** @var Twig $view */
        $view = $container['view'];
        /** @var LoggerInterface $logger */
        $logger = $container['logger'];

        $controller = new DashboardController($view, $logger);

        return $controller->index($request, $response);
    })->setName('dashboard');
};
