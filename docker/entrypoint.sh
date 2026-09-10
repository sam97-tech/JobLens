#!/bin/bash
set -e

php artisan config:clear
php artisan storage:link || true

echo "Waiting for database at ${DB_HOST:-db}:${DB_PORT:-3306}..."
until php -r "exit((new PDO('mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'))) ? 0 : 1);" 2>/dev/null; do
    sleep 2
done

php artisan migrate --force

exec "$@"
