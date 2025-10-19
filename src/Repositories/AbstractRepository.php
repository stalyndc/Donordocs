<?php

declare(strict_types=1);

namespace DonorDocs\Repositories;

use PDO;
use PDOStatement;

abstract class AbstractRepository
{
    protected PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param array<string, mixed> $parameters
     */
    protected function execute(string $query, array $parameters = []): bool
    {
        $statement = $this->prepare($query, $parameters);

        return $statement->execute();
    }

    /**
     * @return array<string, mixed>|null
     * @param array<string, mixed> $parameters
     */
    protected function fetch(string $query, array $parameters = []): ?array
    {
        $statement = $this->prepare($query, $parameters);
        $statement->execute();

        $result = $statement->fetch();

        return $result === false ? null : $result;
    }

    /**
     * @param array<string, mixed> $parameters
     * @return array<int, array<string, mixed>>
     */
    protected function fetchAll(string $query, array $parameters = []): array
    {
        $statement = $this->prepare($query, $parameters);
        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * @param array<string, mixed> $parameters
     */
    private function prepare(string $query, array $parameters = []): PDOStatement
    {
        $statement = $this->connection->prepare($query);

        foreach ($parameters as $key => $value) {
            $statement->bindValue(
                is_int($key) ? $key + 1 : ':' . ltrim((string) $key, ':'),
                $value
            );
        }

        return $statement;
    }
}
