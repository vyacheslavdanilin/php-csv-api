<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\FileUploadService;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Responses\ApiResponse;
use App\Services\FileValidator;

class FileUploadController
{
    public function __construct(
        private FileUploadService $fileUploadService,
        private FileValidator $fileValidator
    ) {
    }

    public function upload(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $uploadedFile = $request->getUploadedFiles()['file'] ?? null;

            $this->fileValidator->validate($uploadedFile);

            $recordCount = $this->fileUploadService->processFile($uploadedFile->getStream()->getMetadata('uri'));

            return ApiResponse::success($response, [
                "message" => "$recordCount records uploaded and saved successfully"
            ]);

        } catch (\Exception $e) {
            return ApiResponse::error($response, $e->getMessage());
        }
    }
}
