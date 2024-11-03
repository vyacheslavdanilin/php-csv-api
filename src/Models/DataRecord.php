<?php

declare(strict_types=1);

namespace App\Models;

class DataRecord
{
    private string $code;
    private string $name;
    private ?string $level1;
    private ?string $level2;
    private ?string $level3;
    private float $price;
    private ?float $price_sp;
    private int $quantity;
    private ?string $propertyFields;
    private ?string $jointPurchases;
    private ?string $unit;
    private ?string $image;
    private bool $showOnMain;
    private ?string $description;

    public function __construct(array $data)
    {
        $this->code = $data['code'];
        $this->name = $data['name'];
        $this->level1 = $data['level1'] ?? null;
        $this->level2 = $data['level2'] ?? null;
        $this->level3 = $data['level3'] ?? null;
        $this->price = (float) str_replace(",", ".", $data['price']);
        $this->price_sp = isset($data['price_sp']) ? (float) str_replace(",", ".", $data['price_sp']) : null;
        $this->quantity = (int) ($data['quantity'] ?? 0);
        $this->propertyFields = $data['propertyFields'] ?? null;
        $this->jointPurchases = $data['jointPurchases'] ?? null;
        $this->unit = $data['unit'] ?? null;
        $this->image = $data['image'] ?? null;
        $this->showOnMain = isset($data['showOnMain']) && $data['showOnMain'] !== '' ? (bool) $data['showOnMain'] : false;
        $this->description = $data['description'] ?? null;
    }

    public function getAttributes(): array
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
            'level1' => $this->level1,
            'level2' => $this->level2,
            'level3' => $this->level3,
            'price' => $this->price,
            'price_sp' => $this->price_sp,
            'quantity' => $this->quantity,
            'propertyFields' => $this->propertyFields,
            'jointPurchases' => $this->jointPurchases,
            'unit' => $this->unit,
            'image' => $this->image,
            'showOnMain' => $this->showOnMain ? 1 : 0,
            'description' => $this->description,
        ];
    }
}
