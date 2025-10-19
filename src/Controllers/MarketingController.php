<?php

declare(strict_types=1);

namespace DonorDocs\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\Views\Twig;

final class MarketingController
{
    public function __construct(
        private readonly Twig $view,
        private readonly LoggerInterface $logger
    ) {
    }

    public function home(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->logger->info('Marketing home requested', [
            'path' => (string) $request->getUri(),
        ]);

        return $this->view->render($response, 'marketing/home.twig', [
            'page' => [
                'title' => 'IRS-Compliant Donation Receipts',
                'description' => 'DonorDocs helps U.S. nonprofits generate IRS-compliant receipts, PDFs, and Excel reports.',
            ],
        ]);
    }

    public function pricing(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->logger->info('Marketing pricing requested', [
            'path' => (string) $request->getUri(),
        ]);

        return $this->view->render($response, 'marketing/pricing.twig', [
            'page' => [
                'title' => 'Pricing',
                'description' => 'Flexible plans for nonprofits to generate donation receipts and reports.',
            ],
        ]);
    }
}
