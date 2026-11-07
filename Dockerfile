FROM php:8.4-fpm-alpine

RUN apk add --no-cache postgresql-dev
RUN docker-php-ext-install pdo_pgsql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/orchem-arena
