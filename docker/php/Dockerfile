FROM php:8.4-fpm-alpine AS base

RUN apk add --no-cache \
        git curl zip unzip bash icu-dev libzip-dev oniguruma-dev \
        postgresql-dev libpq linux-headers $PHPIZE_DEPS \
    && docker-php-ext-install \
        pdo_pgsql pgsql intl zip bcmath opcache pcntl \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del $PHPIZE_DEPS

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# ---------- build stage ----------
FROM base AS build

RUN apk add --no-cache nodejs npm

COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-dev --no-scripts --prefer-dist --no-progress

COPY package.json package-lock.json ./
RUN npm ci

COPY . .
RUN composer dump-autoload --optimize --classmap-authoritative \
    && npm run build \
    && rm -rf node_modules

# ---------- runtime stage ----------
FROM base AS runtime

COPY --from=build /var/www/html /var/www/html

RUN chown -R www-data:www-data storage bootstrap/cache

USER www-data
EXPOSE 9000
CMD ["php-fpm"]
