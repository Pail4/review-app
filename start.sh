#!/bin/bash

if [ ! -e ".env" ]; then
    echo "Create .env file"
    cp .env.example .env
fi

if [ ! -e "database/database.sqlite" ]; then
    echo "Create .env file"
    touch database/database.sqlite
fi

# Если vendor нет — скачиваем Composer и устанавливаем зависимости
if [ ! -d "vendor" ]; then
    echo "Installing PHP dependencies..."
    composer install
    # Запуск Composer через Docker (без Sail)
    # docker run --rm \
    #   -u "$(id -u):$(id -g)" \
    #   -v $(pwd):/var/www/html \
    #   -w /var/www/html \
    #   laravelsail/php84-composer:latest \
    #   composer install
fi

echo "Starting Sail..."
./vendor/bin/sail up -d

# После этого vendor/bin/sail уже есть
echo "Generating application key..."
./vendor/bin/sail artisan key:generate

echo "Done! Application is running."
