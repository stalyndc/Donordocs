<?php

declare(strict_types=1);

use DonorDocs\Controllers\DashboardController;
use DonorDocs\Controllers\MarketingController;
use Slim\App;

return static function (App $app, array $container): void {
    $marketingController = new MarketingController($container['view'], $container['logger']);
    $dashboardController = new DashboardController($container['view'], $container['logger']);

    $app->get('/', [$marketingController, 'home'])->setName('home');
    $app->get('/pricing', [$marketingController, 'pricing'])->setName('pricing');
    $app->get('/privacy', [$marketingController, 'privacy'])->setName('privacy');
    $app->get('/terms', [$marketingController, 'terms'])->setName('terms');
    $app->get('/robots.txt', [$marketingController, 'robots'])->setName('robots');
    $app->get('/sitemap.xml', [$marketingController, 'sitemap'])->setName('sitemap');

    $app->get('/dashboard', [$dashboardController, 'index'])->setName('dashboard');
};
