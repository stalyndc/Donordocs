<?php

declare(strict_types=1);

use DonorDocs\Controllers\MarketingController;
use DonorDocs\Database\ConnectionFactory;
use DonorDocs\Repositories\DonationRepository;
use DonorDocs\Repositories\DonorRepository;
use DonorDocs\Repositories\OrgSettingsRepository;
use Dotenv\Dotenv;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use PDO;
use Slim\Exception\HttpNotFoundException;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Throwable;

require __DIR__ . '/../vendor/autoload.php';

$rootPath = dirname(__DIR__);

if (file_exists($rootPath . '/.env')) {
    Dotenv::createImmutable($rootPath)->safeLoad();
} else {
    Dotenv::createImmutable($rootPath, '.env.example')->safeLoad();
}

$settings = require $rootPath . '/config/settings.php';

date_default_timezone_set($settings['timezone']);

if (! is_dir($rootPath . '/storage/logs')) {
    mkdir($rootPath . '/storage/logs', 0775, true);
}

$logLevel = $settings['app']['debug'] ? Level::Debug : Level::Info;
$logger = new Logger($settings['logger']['name']);
$logger->pushProcessor(new PsrLogMessageProcessor());
$logger->pushHandler(new StreamHandler($settings['logger']['path'], $logLevel));

$pdo = null;

try {
    $pdo = ConnectionFactory::create($settings['db'], $logger);
} catch (RuntimeException $exception) {
    if ($settings['app']['debug']) {
        throw $exception;
    }
}

$twig = Twig::create($rootPath . '/templates', [
    'cache' => false,
]);
$twig->getEnvironment()->addGlobal('app', [
    'url' => $settings['app']['url'],
]);

$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->add(TwigMiddleware::create($app, $twig));

$errorMiddleware = $app->addErrorMiddleware(
    $settings['app']['debug'],
    true,
    true,
    $logger
);

$responseFactory = $app->getResponseFactory();
$marketingErrorController = new MarketingController($twig, $logger);

$errorMiddleware->setErrorHandler(
    HttpNotFoundException::class,
    static function (ServerRequestInterface $request, Throwable $exception) use ($marketingErrorController, $responseFactory) {
        $response = $responseFactory->createResponse();

        return $marketingErrorController->notFound($request, $response);
    }
);

$repositories = [];

if ($pdo instanceof PDO) {
    $repositories = [
        'orgSettings' => new OrgSettingsRepository($pdo),
        'donors' => new DonorRepository($pdo),
        'donations' => new DonationRepository($pdo),
    ];
}

return [
    'app' => $app,
    'container' => [
        'settings' => $settings,
        'logger' => $logger,
        'db' => $pdo,
        'view' => $twig,
        'repositories' => $repositories,
    ],
];
