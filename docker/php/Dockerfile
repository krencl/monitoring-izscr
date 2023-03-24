FROM php:8.2-fpm

RUN echo $TZ > /etc/timezone

RUN apt update && apt -qy install zip
RUN docker-php-ext-install mysqli

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /www

CMD php-fpm
