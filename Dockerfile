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

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
  && composer install --no-dev --optimize-autoloader --no-interaction --no-scripts \
  && mkdir -p storage bootstrap/cache storage/framework/cache/data storage/framework/sessions storage/framework/views \
  && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 10000

CMD ["sh", "-c", "php artisan package:discover --no-interaction && php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan serve --host=0.0.0.0 --port=10000"]
