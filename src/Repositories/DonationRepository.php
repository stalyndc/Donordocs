<?php

declare(strict_types=1);

namespace DonorDocs\Repositories;

use DateTimeImmutable;
use InvalidArgumentException;
use PDO;

final class DonationRepository extends AbstractRepository
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function paginate(int $limit = 25, int $offset = 0): array
    {
        $statement = $this->connection->prepare(
            'SELECT donations.*, donors.name AS donor_name
             FROM donations
             INNER JOIN donors ON donors.id = donations.donor_id
             ORDER BY donations.donation_date DESC, donations.id DESC
             LIMIT :limit OFFSET :offset'
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
        return $this->fetch(
            'SELECT donations.*, donors.name AS donor_name
             FROM donations
             INNER JOIN donors ON donors.id = donations.donor_id
             WHERE donations.id = :id',
            ['id' => $id]
        );
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function create(array $payload): int
    {
        $data = $this->filterPayload($payload);

        if (! isset($data['donor_id'], $data['donation_date'], $data['amount'])) {
            throw new InvalidArgumentException('Donation payload missing required fields.');
        }

        $data['donation_date'] = $this->normalizeDate($data['donation_date']);

        $columns = array_keys($data);
        $placeholders = array_map(static fn (string $column): string => ':' . $column, $columns);

        $this->execute(
            sprintf(
                'INSERT INTO donations (%s) VALUES (%s)',
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

        if (isset($data['donation_date'])) {
            $data['donation_date'] = $this->normalizeDate($data['donation_date']);
        }

        $setClauses = [];
        foreach (array_keys($data) as $column) {
            $setClauses[] = sprintf('%s = :%s', $column, $column);
        }

        $data['id'] = $id;

        $this->execute(
            sprintf('UPDATE donations SET %s WHERE id = :id', implode(', ', $setClauses)),
            $data
        );
    }

    public function delete(int $id): void
    {
        $this->execute('DELETE FROM donations WHERE id = :id', ['id' => $id]);
    }

    public function incrementReceiptSequence(int $year): string
    {
        $yearString = (string) $year;

        $this->connection->beginTransaction();

        try {
            $current = $this->fetch(
                'SELECT last_number FROM receipt_sequences WHERE year = :year FOR UPDATE',
                ['year' => $yearString]
            );

            $nextNumber = ($current['last_number'] ?? 0) + 1;

            $this->execute(
                'INSERT INTO receipt_sequences (year, last_number) VALUES (:year, :last_number)
                 ON DUPLICATE KEY UPDATE last_number = :last_number',
                [
                    'year' => $yearString,
                    'last_number' => $nextNumber,
                ]
            );

            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();
            throw $exception;
        }

        return sprintf('DD-%s-%06d', $yearString, $nextNumber);
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    private function filterPayload(array $payload): array
    {
        $allowed = [
            'donor_id',
            'donation_date',
            'amount',
            'method',
            'designation',
            'notes',
            'receipt_number',
            'receipt_pdf_path',
        ];

        return array_intersect_key($payload, array_flip($allowed));
    }

    private function normalizeDate(string $value): string
    {
        return (new DateTimeImmutable($value))->format('Y-m-d');
    }
}
