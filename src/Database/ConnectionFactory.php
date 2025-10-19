<?php

declare(strict_types=1);

namespace DonorDocs\Database;

use PDO;
use PDOException;
use Psr\Log\LoggerInterface;
use RuntimeException;

final class ConnectionFactory
{
    /**
     * @param array<string, mixed> $config
     */
    public static function create(array $config, LoggerInterface $logger): PDO
    {
        $dsn = sprintf(
            '%s:host=%s;port=%d;dbname=%s;charset=%s',
            $config['driver'] ?? 'mysql',
            $config['host'] ?? '127.0.0.1',
            $config['port'] ?? 3306,
            $config['database'] ?? '',
            $config['charset'] ?? 'utf8mb4'
        );

        try {
            $pdo = new PDO(
                $dsn,
                $config['username'] ?? '',
                $config['password'] ?? '',
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $exception) {
            $logger->error('Database connection failed: {message}', ['message' => $exception->getMessage()]);
            throw new RuntimeException('Unable to connect to the database.', (int) $exception->getCode(), $exception);
        }

        return $pdo;
    }
}
