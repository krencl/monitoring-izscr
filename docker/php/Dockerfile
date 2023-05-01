FROM php:8.2-fpm

ENV TZ=Europe/Prague
RUN echo $TZ > /etc/timezone

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt update && apt -qy install zip libc-client-dev libkrb5-dev

RUN docker-php-ext-install mysqli

RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl && \
    docker-php-ext-install imap && \
    docker-php-ext-enable imap

WORKDIR /www

CMD php-fpm
