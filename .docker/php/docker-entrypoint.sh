#!/bin/sh
set -e

# Borrowed from https://github.com/api-platform/api-platform/blob/master/api/docker/php/docker-entrypoint.sh

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
    set -- php-fpm "$@"
fi

if [ "$1" = 'php-fpm' ] || [ "$1" = 'php' ] || [ "$1" = 'bin/console' ]; then
    PHP_INI_RECOMMENDED="$PHP_INI_DIR/php.ini-production"
    if [ "$APP_ENV" != 'prod' ]; then
        PHP_INI_RECOMMENDED="$PHP_INI_DIR/php.ini-development"
    fi
    ln -sf "$PHP_INI_RECOMMENDED" "$PHP_INI_DIR/php.ini"

    mkdir -p var/cache var/log
    setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX var || true
    setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX var || true

    if [ "$APP_ENV" = 'prod' ]; then
        php bin/console doctrine:migrations:migrate --allow-no-migration --no-interaction
    fi
fi

# Update xdebug config if exists
if test -f "$XDEBUG_INI_PATH"; then
    printf '%s\n' 'xdebug.remote_enable=0' 'xdebug.remote_host=' 'xdebug.remote_port=9097' 'xdebug.remote_autostart=1' >> $XDEBUG_INI_PATH
fi

exec docker-php-entrypoint "$@"
