<?php

declare(strict_types=1);

namespace DonorDocs\Repositories;

use PDO;

final class OrgSettingsRepository extends AbstractRepository
{
    /**
     * @return array<string, mixed>|null
     */
    public function getSettings(): ?array
    {
        return $this->fetch('SELECT * FROM org_settings ORDER BY id DESC LIMIT 1');
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function upsert(array $payload): void
    {
        $data = $this->filterPayload($payload);

        if ($data === []) {
            return;
        }

        $existing = $this->getSettings();

        if ($existing !== null) {
            $setClauses = [];
            foreach (array_keys($data) as $column) {
                $setClauses[] = sprintf('%s = :%s', $column, $column);
            }

            $data['id'] = $existing['id'];

            $this->execute(
                sprintf('UPDATE org_settings SET %s WHERE id = :id', implode(', ', $setClauses)),
                $data
            );

            return;
        }

        $columns = array_keys($data);
        $placeholders = array_map(static fn (string $column): string => ':' . $column, $columns);

        $this->execute(
            sprintf(
                'INSERT INTO org_settings (%s) VALUES (%s)',
                implode(', ', $columns),
                implode(', ', $placeholders)
            ),
            $data
        );
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    private function filterPayload(array $payload): array
    {
        $allowed = [
            'org_name',
            'org_address',
            'org_ein',
            'city',
            'state',
            'zip',
            'logo_path',
            'signature_path',
            'signer_name',
            'signer_title',
            'default_receipt_text',
            'timezone',
        ];

        return array_intersect_key($payload, array_flip($allowed));
    }
}
