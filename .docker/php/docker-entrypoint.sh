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

    if grep -q DATABASE_URL= .env; then
        echo "Waiting for database to be ready..."
        ATTEMPTS_LEFT_TO_REACH_DATABASE=30
        until [ $ATTEMPTS_LEFT_TO_REACH_DATABASE -eq 0 ] || DATABASE_ERROR=$(php bin/console dbal:run-sql -q "SELECT 1" 2>&1); do
            sleep 1
            ATTEMPTS_LEFT_TO_REACH_DATABASE=$((ATTEMPTS_LEFT_TO_REACH_DATABASE - 1))
            echo "Still waiting for database to be ready... Or maybe the database is not reachable. $ATTEMPTS_LEFT_TO_REACH_DATABASE attempts left."
        done

        if [ $ATTEMPTS_LEFT_TO_REACH_DATABASE -eq 0 ]; then
            echo "The database is not up or not reachable:"
            echo "$DATABASE_ERROR"
            exit 1
        else
            echo "The database is now ready and reachable"
        fi
    fi

    if [ "$APP_ENV" = 'prod' ]; then
        php bin/console doctrine:migrations:migrate --allow-no-migration --no-interaction
    fi

fi

exec docker-php-entrypoint "$@"
