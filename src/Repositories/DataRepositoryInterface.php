<?php

declare(strict_types=1);

namespace App\Repositories;

interface DataRepositoryInterface
{
    public function saveBatch(array $dataRecords): void;
    public function fetchAll(): array;
}
