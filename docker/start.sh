#!/usr/bin/env bash
set -e

PORT="${PORT:-8080}"

if [ ! -f /var/www/html/.env ]; then
  cp /var/www/html/.env.example /var/www/html/.env
fi

export APP_ENV="${APP_ENV:-production}"
export APP_DEBUG="${APP_DEBUG:-false}"
export APP_URL="${APP_URL:-http://localhost:${PORT}}"
export DB_CONNECTION="${DB_CONNECTION:-sqlite}"
export DB_DATABASE="${DB_DATABASE:-/var/data/database.sqlite}"

mkdir -p "$(dirname "${DB_DATABASE}")"
touch "${DB_DATABASE}"

if ! grep -q "^APP_KEY=base64:" /var/www/html/.env; then
  php /var/www/html/artisan key:generate --force
fi

if [ ! -L /var/www/html/public/storage ]; then
  php /var/www/html/artisan storage:link || true
fi

php /var/www/html/artisan migrate --force

SEED_FLAG="/var/data/.seeded"
if [ ! -f "${SEED_FLAG}" ]; then
  php /var/www/html/artisan db:seed --force
  touch "${SEED_FLAG}"
fi
php /var/www/html/artisan config:cache || true
php /var/www/html/artisan route:cache || true
php /var/www/html/artisan view:cache || true

envsubst '$PORT' < /etc/nginx/conf.d/default.conf.template > /etc/nginx/conf.d/default.conf

php-fpm -D
nginx -g 'daemon off;'
