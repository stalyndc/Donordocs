<?php

declare(strict_types=1);

namespace DonorDocs\Repositories;

use PDO;

final class DonorRepository extends AbstractRepository
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function paginate(int $limit = 25, int $offset = 0): array
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM donors ORDER BY created_at DESC LIMIT :limit OFFSET :offset'
        );
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function find(int $id): ?array
    {
        return $this->fetch('SELECT * FROM donors WHERE id = :id', ['id' => $id]);
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function create(array $payload): int
    {
        $data = $this->filterPayload($payload);

        if ($data === []) {
            throw new \InvalidArgumentException('No donor fields provided.');
        }

        $columns = array_keys($data);
        $placeholders = array_map(static fn (string $column): string => ':' . $column, $columns);

        $this->execute(
            sprintf(
                'INSERT INTO donors (%s) VALUES (%s)',
                implode(', ', $columns),
                implode(', ', $placeholders)
            ),
            $data
        );

        return (int) $this->connection->lastInsertId();
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function update(int $id, array $payload): void
    {
        $data = $this->filterPayload($payload);

        if ($data === []) {
            return;
        }

        $setClauses = [];
        foreach (array_keys($data) as $column) {
            $setClauses[] = sprintf('%s = :%s', $column, $column);
        }

        $data['id'] = $id;

        $this->execute(
            sprintf('UPDATE donors SET %s WHERE id = :id', implode(', ', $setClauses)),
            $data
        );
    }

    public function delete(int $id): void
    {
        $this->execute('DELETE FROM donors WHERE id = :id', ['id' => $id]);
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    private function filterPayload(array $payload): array
    {
        $allowed = [
            'name',
            'email',
            'address',
        ];

        return array_intersect_key($payload, array_flip($allowed));
    }
}
