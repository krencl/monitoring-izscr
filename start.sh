#!/bin/bash

docker compose build && \
  docker compose -p monitoring up --detach --force-recreate && \
  docker exec -it monitoring-php-1 /usr/bin/composer install
