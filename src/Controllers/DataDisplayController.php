<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\DataRepositoryInterface;
use App\Responses\ApiResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DataDisplayController
{
    public function __construct(private DataRepositoryInterface $repository)
    {
    }

    public function getData(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $dataRecords = $this->repository->fetchAll();
        $data = array_map(fn ($record) => $record->getAttributes(), $dataRecords);

        return ApiResponse::success($response, $data);
    }
}
