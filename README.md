## Сервис отзывов

Тестовое Laravel + sail приложение 

### Разворачивание

#### Через скрипт
Выполнить
`./start.sh`

#### Вручную

1. Скопировать .env.example в .env

`cp .env.example .env`

2. Установить зависимости

`composer install`

3. Поднять sail

`./vendor/bin/sail up -d`

4. Создать ключ

`./vendor/bin/sail artisan key:generate`