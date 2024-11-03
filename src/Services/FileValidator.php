<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\FileUploadException;
use Psr\Http\Message\UploadedFileInterface;

class FileValidator
{
    public function validate(UploadedFileInterface $file): void
    {
        if ($file === null) {
            throw new FileUploadException("No file uploaded");
        }

        if ($file->getError() !== UPLOAD_ERR_OK) {
            throw new FileUploadException("File upload error");
        }

        if ($file->getSize() > 10 * 1024 * 1024) {
            throw new FileUploadException("File is too large");
        }

        if ($file->getClientMediaType() !== 'text/csv') {
            throw new FileUploadException("Invalid file type. Only CSV files are allowed");
        }
    }
}
