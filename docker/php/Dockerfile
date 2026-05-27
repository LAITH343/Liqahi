FROM php:8.4-fpm-alpine AS base

RUN apk add --no-cache icu-libs libzip libpq \
    && apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS icu-dev libzip-dev postgresql-dev linux-headers \
    && docker-php-ext-install \
        pdo_pgsql pgsql intl zip bcmath opcache pcntl \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps \
    && rm -rf /tmp/* /var/cache/apk/* /usr/local/lib/php/.registry

WORKDIR /var/www/html

# ---------- build stage ----------
FROM base AS build

RUN apk add --no-cache git unzip nodejs npm
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-dev --no-scripts --prefer-dist --no-progress

COPY package.json package-lock.json ./
RUN npm ci

COPY . .
RUN composer dump-autoload --optimize --classmap-authoritative \
    && npm run build \
    && rm -rf node_modules \
    && composer clear-cache

# ---------- runtime stage ----------
FROM base AS runtime

COPY --from=build /var/www/html /var/www/html

RUN chown -R www-data:www-data storage bootstrap/cache

USER www-data
EXPOSE 9000
CMD ["php-fpm"]
