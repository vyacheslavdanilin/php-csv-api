# CSV API
API для загрузки и отображения данных из CSV-файлов

## Запуск

1. Скопировать конфиг:

```
cp .env.example .env
```

2. Запустить контейнеры:

```
docker compose up -d --build
```

## Миграции
3. Запустить миграции:

```
docker exec php-csv-api-php-1 phinx migrate -c phinx.php
```

## API Endpoints
- Получение данных:
    - GET http://localhost:8080/api/data

- Загрузка CSV-файла:
    - POST http://localhost:8080/api/upload


## TODO
- Добавить юнит и интеграционные тесты
- Добавить DI-контейнер если проект будет расти
- Заменить текущую обработку CSV на пакет (например, league/csv)
- Добавить документацию по API (например, с использованием Swagger/OpenAPI)
