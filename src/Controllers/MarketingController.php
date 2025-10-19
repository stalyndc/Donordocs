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

        return $this->render($request, $response, 'marketing/home.twig', [
            'page' => [
                'title' => 'DonorDocs — IRS-Compliant Donation Receipts for U.S. Nonprofits',
                'description' => 'Generate branded, audit-ready receipts and Excel reports in seconds. Built for U.S. 501(c)(3) nonprofits.',
            ],
        ]);
    }

    public function pricing(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->logger->info('Marketing pricing requested', [
            'path' => (string) $request->getUri(),
        ]);

        return $this->render($request, $response, 'marketing/pricing.twig', [
            'page' => [
                'title' => 'DonorDocs Pricing',
                'description' => 'Flexible pricing plans for nonprofits to generate donation receipts and reports.',
            ],
        ]);
    }

    public function privacy(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->logger->info('Privacy policy requested', [
            'path' => (string) $request->getUri(),
        ]);

        return $this->render($request, $response, 'marketing/privacy.twig', [
            'page' => [
                'title' => 'Privacy Policy',
                'description' => 'Learn how DonorDocs handles data privacy for nonprofits and donors.',
            ],
        ]);
    }

    public function terms(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->logger->info('Terms of service requested', [
            'path' => (string) $request->getUri(),
        ]);

        return $this->render($request, $response, 'marketing/terms.twig', [
            'page' => [
                'title' => 'Terms of Service',
                'description' => 'Review the DonorDocs terms governing use of the platform.',
            ],
        ]);
    }

    public function notFound(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->logger->warning('Page not found', [
            'path' => (string) $request->getUri(),
        ]);

        $response = $response->withStatus(404);

        return $this->render($request, $response, 'errors/404.twig', [
            'page' => [
                'title' => 'Page Not Found',
                'description' => 'The page you were looking for could not be found.',
            ],
        ]);
    }

    public function robots(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->logger->info('Robots.txt requested', [
            'path' => (string) $request->getUri(),
        ]);

        $baseUrl = $this->baseUrl($request);
        $body = <<<TXT
User-agent: *
Disallow:
Sitemap: {$baseUrl}/sitemap.xml
TXT;

        $response->getBody()->write($body);

        return $response
            ->withHeader('Content-Type', 'text/plain; charset=utf-8');
    }

    public function sitemap(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->logger->info('Sitemap requested', [
            'path' => (string) $request->getUri(),
        ]);

        $baseUrl = $this->baseUrl($request);
        $body = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url><loc>{$baseUrl}/</loc></url>
  <url><loc>{$baseUrl}/pricing</loc></url>
  <url><loc>{$baseUrl}/dashboard</loc></url>
  <url><loc>{$baseUrl}/privacy</loc></url>
  <url><loc>{$baseUrl}/terms</loc></url>
</urlset>
XML;

        $response->getBody()->write($body);

        return $response
            ->withHeader('Content-Type', 'application/xml; charset=utf-8');
    }

    private function render(
        ServerRequestInterface $request,
        ResponseInterface $response,
        string $template,
        array $data = []
    ): ResponseInterface {
        $data['currentPath'] = $request->getUri()->getPath();

        if (! isset($data['page'])) {
            $data['page'] = [];
        }

        $data['page'] += [
            'title' => 'DonorDocs',
            'description' => 'DonorDocs helps U.S. nonprofits generate IRS-compliant donation receipts and reports.',
        ];

        return $this->view->render($response, $template, $data);
    }

    private function baseUrl(ServerRequestInterface $request): string
    {
        $uri = $request->getUri();
        $host = $uri->getHost();

        if ($host !== '') {
            $scheme = $uri->getScheme() !== '' ? $uri->getScheme() : 'https';
            $port = $uri->getPort();
            $portSuffix = ($port !== null && ! in_array($port, [80, 443], true)) ? ':' . $port : '';

            return rtrim(sprintf('%s://%s%s', $scheme, $host, $portSuffix), '/');
        }

        return rtrim($_ENV['APP_URL'] ?? 'https://donordocs.com', '/');
    }
}
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
