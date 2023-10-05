#!/bin/sh
set -e

# Borrowed from https://github.com/api-platform/api-platform/blob/master/api/docker/php/docker-entrypoint.sh

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
    set -- php-fpm "$@"
fi

if [ "$1" = 'php-fpm' ] || [ "$1" = 'php' ] || [ "$1" = 'bin/console' ]; then
    setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX var || true
    setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX var || true

    if [ "$APP_ENV" = 'dev' ]; then
        composer install --prefer-dist --no-progress --no-interaction || true
    fi

    if [ "$APP_ENV" = 'prod' ]; then
        php bin/console doctrine:migrations:migrate --allow-no-migration --no-interaction
    fi

fi

exec docker-php-entrypoint "$@"
