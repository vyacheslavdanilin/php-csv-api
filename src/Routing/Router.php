<?php

declare(strict_types=1);

namespace App\Routing;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Nyholm\Psr7\Factory\Psr17Factory;

class Router
{
    private array $routes = [];

    public function __construct(private Psr17Factory $responseFactory)
    {
    }

    public function add(string $method, string $path, callable $handler): void
    {
        $this->routes[$method][$path] = $handler;
    }

    public function dispatch(ServerRequestInterface $request): ResponseInterface
    {
        $method = $request->getMethod();
        $path = $request->getUri()->getPath();

        if (isset($this->routes[$method][$path])) {
            $handler = $this->routes[$method][$path];
            $response = $this->responseFactory->createResponse();

            return call_user_func($handler, $request, $response);
        } else {
            $response = $this->responseFactory->createResponse(404);
            $response->getBody()->write(json_encode(["error" => "404 Not Found"]));

            return $response->withHeader('Content-Type', 'application/json');
        }
    }
}
