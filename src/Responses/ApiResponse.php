<?php

declare(strict_types=1);

namespace App\Responses;

use Psr\Http\Message\ResponseInterface;

class ApiResponse
{
    public static function success(ResponseInterface $response, array $data, int $statusCode = 200): ResponseInterface
    {
        $response->getBody()->write(json_encode([
            "status" => "success",
            "data" => $data
        ]));

        return $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
    }

    public static function error(ResponseInterface $response, string $message, int $statusCode = 400): ResponseInterface
    {
        $response->getBody()->write(json_encode([
            "status" => "error",
            "message" => $message
        ]));

        return $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
    }
}
