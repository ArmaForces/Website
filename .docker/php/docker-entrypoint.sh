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
    # https://github.com/qoomon/docker-host/blob/master/entrypoint.sh
    function resolveHost {
        getent ahostsv4 "$1" | head -n1 | cut -d' ' -f1
    }
    DOCKER_HOST='host.docker.internal'
    docker_host_ip="$(resolveHost "$DOCKER_HOST")"

    if [ "$docker_host_ip" ]
    then
        echo "Docker Host: $docker_host_ip ($DOCKER_HOST)"
    else
        docker_host_ip=$(ip -4 route show default | cut -d' ' -f3)
        if [ "$docker_host_ip" ]
        then
            echo "Docker Host: $docker_host_ip (default gateway)"
        fi
    fi

    printf '%s\n' 'xdebug.remote_enable=0' "xdebug.remote_host=${docker_host_ip}" 'xdebug.remote_port=9097' 'xdebug.remote_autostart=1' >> $XDEBUG_INI_PATH
fi

exec docker-php-entrypoint "$@"
