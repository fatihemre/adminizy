FROM php:8.1-fpm-alpine

RUN apk add icu-dev
RUN docker-php-ext-install pdo pdo_mysql bcmath
RUN docker-php-ext-configure intl && docker-php-ext-install intl

RUN chown -R www-data:www-data /var/www

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

COPY ./composer.* ./

RUN composer install --prefer-dist --no-dev --no-scripts --no-progress --no-interaction

COPY ./ .

RUN composer dump-autoload --optimize