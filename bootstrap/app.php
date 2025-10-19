<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use PDO;
use PDOException;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Slim\Exception\HttpNotFoundException;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;
use DonorDocs\Controllers\MarketingController;

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

$dsn = sprintf(
    '%s:host=%s;port=%d;dbname=%s;charset=%s',
    $settings['db']['driver'],
    $settings['db']['host'],
    $settings['db']['port'],
    $settings['db']['database'],
    $settings['db']['charset']
);

$pdo = null;

try {
    $pdo = new PDO(
        $dsn,
        $settings['db']['username'],
        $settings['db']['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $exception) {
    $logger->error('Database connection failed: {message}', ['message' => $exception->getMessage()]);
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
return [
    'app' => $app,
    'container' => [
        'settings' => $settings,
        'logger' => $logger,
        'db' => $pdo,
        'view' => $twig,
    ],
];
