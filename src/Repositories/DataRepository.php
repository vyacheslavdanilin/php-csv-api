<?php

declare (strict_types=1);

namespace App\Repositories;

use App\Models\DataRecord;
use PDO;
use PDOException;
use Psr\Log\LoggerInterface;

class DataRepository implements DataRepositoryInterface
{
    public function __construct(
        private PDO $pdo,
        private LoggerInterface $logger
    ) {
    }

    public function saveBatch(array $dataRecords): void
    {
        if (empty($dataRecords)) {
            return;
        }

        $fields = [
            'code', 'name', 'level1', 'level2', 'level3', 'price', 'price_sp', 'quantity',
            'propertyFields', 'jointPurchases', 'unit', 'image', 'showOnMain', 'description',
        ];

        $placeholders = '(' . implode(', ', array_fill(0, count($fields), '?')) . ')';
        $sql = 'INSERT INTO data_records (' . implode(', ', $fields) . ') VALUES ';
        $allPlaceholders = [];
        $values = [];

        foreach ($dataRecords as $dataRecord) {
            if (!$dataRecord instanceof DataRecord) {
                throw new \InvalidArgumentException('Invalid data record');
            }

            $attributes = $dataRecord->getAttributes();
            foreach ($fields as $field) {
                $values[] = $attributes[$field] ?? null;
            }
            $allPlaceholders[] = $placeholders;
        }

        $sql .= implode(', ', $allPlaceholders);

        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($values);
            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            $this->logger->error('Error saving data batch: ' . $e->getMessage());

            throw new \RuntimeException('Failed to save data batch', 0, $e);
        }
    }

    public function fetchAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM data_records');
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn ($row) => new DataRecord($row), $results);
    }
}
