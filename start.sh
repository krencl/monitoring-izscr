#!/bin/bash

docker compose build
docker compose -p monitoring-izscr up --detach --force-recreate
docker exec -it monitoring-izscr-php-1 /usr/bin/composer install
