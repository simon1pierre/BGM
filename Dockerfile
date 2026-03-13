FROM node:20-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json vite.config.js tailwind.config.js ./
COPY resources ./resources

RUN npm ci && npm run build

FROM php:8.4-cli-bookworm

WORKDIR /var/www/html

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update \
  && apt-get install -y --no-install-recommends \
    git \
    unzip \
    curl \
    libjpeg62-turbo-dev \
    libpng-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    libpq-dev \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    bcmath \
    gd \
    zip \
    opcache \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/*

COPY . /var/www/html
COPY --from=frontend /app/public/build /var/www/html/public/build

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
  && composer install --no-dev --optimize-autoloader --no-interaction --no-scripts \
  && mkdir -p storage bootstrap/cache storage/framework/cache/data storage/framework/sessions storage/framework/views \
  && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 10000

CMD ["sh", "-c", "php artisan package:discover --no-interaction && php artisan storage:link || true && if [ \"${SEED_ON_START:-true}\" = \"true\" ]; then php artisan migrate:fresh --force --seed; else php artisan migrate --force; fi && php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan serve --host=0.0.0.0 --port=10000"]
