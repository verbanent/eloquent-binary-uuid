FROM php:fpm-buster
RUN pecl install xdebug && docker-php-ext-enable xdebug