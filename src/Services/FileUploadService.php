<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\DataRecord;
use App\Repositories\DataRepositoryInterface;
use App\Services\FileParserService;
use Psr\Log\LoggerInterface;

class FileUploadService
{
    private const BATCH_SIZE = 100;

    public function __construct(
        private DataRepositoryInterface $dataRepository,
        private FileParserService $fileParserService,
        private LoggerInterface $logger
    ) {
    }

    public function processFile(string $filePath): int
    {
        $recordCount = 0;

        try {
            foreach ($this->fileParserService->parseInBatches($filePath, self::BATCH_SIZE) as $rows) {
                $batch = [];
                foreach ($rows as $row) {
                    $dataRecord = new DataRecord($row);
                    $batch[] = $dataRecord;
                    $recordCount++;
                }

                $this->dataRepository->saveBatch($batch);
            }
        } catch (\Exception $e) {
            $this->logger->error('Processing file failed: ' . $e->getMessage(), ['exception' => $e]);
            throw $e;
        }

        return $recordCount;
    }

}
