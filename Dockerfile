FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress

FROM php:8.2-fpm-bullseye

RUN apt-get update \
  && apt-get install -y --no-install-recommends \
    nginx \
    git \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    sqlite3 \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    pdo_sqlite \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    opcache \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY --from=vendor /app/vendor /var/www/html/vendor
COPY . /var/www/html
COPY docker/nginx.conf.template /etc/nginx/conf.d/default.conf.template
COPY docker/start.sh /start.sh

RUN chmod +x /start.sh \
  && mkdir -p storage bootstrap/cache \
  && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 8080

CMD ["/start.sh"]
