#!/bin/sh
set -e

HOST_IP=$(/sbin/ip route | awk '/default/ { print $3 }')

sed -i "s|xdebug.remote_enable=.*|xdebug.remote_enable=1|" $XDEBUG_INI_PATH
sed -i "s|xdebug.remote_host=.*|xdebug.remote_host=$HOST_IP|" $XDEBUG_INI_PATH
echo "XDEBUG ON"

pkill -USR2 -o php-fpm
