#!/bin/sh
set -e

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
fi

sed -i "s|;zend_extension=xdebug|zend_extension=xdebug|" $XDEBUG_INI_PATH
sed -i "s|xdebug.remote_host=.*|xdebug.remote_host=${docker_host_ip}|" $XDEBUG_INI_PATH
echo "XDEBUG ON"

pkill -USR2 -o php-fpm
