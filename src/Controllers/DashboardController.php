<?php

declare(strict_types=1);

namespace DonorDocs\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\Views\Twig;

final class DashboardController
{
    public function __construct(
        private readonly Twig $view,
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->logger->info('Dashboard requested', [
            'path' => (string) $request->getUri(),
        ]);

        return $this->view->render($response, 'dashboard.twig', [
            'page' => [
                'title' => 'Dashboard',
            ],
            'metrics' => [
                'totalDonations' => 0,
                'monthlyDonations' => 0,
                'donorCount' => 0,
            ],
        ]);
    }
}
