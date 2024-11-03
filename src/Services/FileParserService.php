<?php

declare(strict_types=1);

namespace App\Services;

class FileParserService
{
    private array $columnMapping = [
        'Код' => 'code',
        'Наименование' => 'name',
        'Уровень1' => 'level1',
        'Уровень2' => 'level2',
        'Уровень3' => 'level3',
        'Цена' => 'price',
        'ЦенаСП' => 'price_sp',
        'Количество' => 'quantity',
        'Поля свойств' => 'propertyFields',
        'Совместные покупки' => 'jointPurchases',
        'Единица измерения' => 'unit',
        'Картинка' => 'image',
        'Выводить на главной' => 'showOnMain',
        'Описание' => 'description',
    ];

    public function parseInBatches(string $filePath, int $batchSize): \Generator
    {
        if (($handle = fopen($filePath, 'r')) === false) {
            throw new \RuntimeException("Cannot open file: $filePath");
        }

        $header = fgetcsv($handle, 1000, ';');
        $header = array_map(fn ($column) => $this->columnMapping[$column] ?? null, $header);
        $header = array_filter($header);

        $batch = [];
        while (($data = fgetcsv($handle, 1000, ';')) !== false) {
            $data = array_slice($data, 0, count($header));
            if (count($data) === count($header)) {
                $row = array_combine($header, $data);
                if ($row) {
                    $batch[] = $row;
                }
            }

            if (count($batch) === $batchSize) {
                yield $batch;
                $batch = [];
            }
        }

        if (!empty($batch)) {
            yield $batch;
        }

        fclose($handle);
    }
}
