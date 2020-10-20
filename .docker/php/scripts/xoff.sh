#!/bin/sh
set -e

sed -i "s|xdebug.remote_enable=.*|xdebug.remote_enable=0|" $XDEBUG_INI_PATH
echo "XDEBUG OFF"

pkill -USR2 -o php-fpm
