#!/bin/bash
set -e

php artisan config:clear
php artisan storage:link || true
php artisan migrate --force

exec "$@"
