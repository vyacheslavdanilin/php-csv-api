<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\DataDisplayController;
use App\Controllers\FileUploadController;
use App\Repositories\DataRepository;
use App\Services\FileParserService;
use App\Services\FileUploadService;
use App\Routing\Router;
use App\Services\FileValidator;
use Dotenv\Dotenv;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Nyholm\Psr7Server\ServerRequestCreator;
use Nyholm\Psr7\Factory\Psr17Factory;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

try {
    $pdo = new \PDO(
        "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset=utf8mb4",
        $_ENV['DB_USER'],
        $_ENV['DB_PASS'],
        [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );

    $logger = new Logger('csvLogger');
    $logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/csv_errors.log', Logger::WARNING));

    $dataRepository = new DataRepository($pdo, $logger);
    $fileParserService = new FileParserService($logger);
    $fileUploadService = new FileUploadService($dataRepository, $fileParserService, $logger);
    $fileUploadController = new FileUploadController($fileUploadService, new FileValidator());
    $dataDisplayController = new DataDisplayController($dataRepository);

    $psr17Factory = new Psr17Factory();
    $serverRequestCreator = new ServerRequestCreator(
        $psr17Factory,
        $psr17Factory,
        $psr17Factory,
        $psr17Factory
    );

    $request = $serverRequestCreator->fromGlobals();
    $router = new Router($psr17Factory);

    $router->add('POST', '/api/upload', [$fileUploadController, 'upload']);
    $router->add('GET', '/api/data', [$dataDisplayController, 'getData']);

    $response = $router->dispatch($request);

    foreach ($response->getHeaders() as $name => $values) {
        foreach ($values as $value) {
            header(sprintf('%s: %s', $name, $value), false);
        }
    }

    echo $response->getBody();
} catch (\PDOException $e) {
    $this->logger->error('Database connection error: ' . $e->getMessage());

    die("An error occurred");
}
