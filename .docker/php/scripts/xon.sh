#!/bin/sh
set -e

sed -i "s|;zend_extension = xdebug|zend_extension = xdebug|" $XDEBUG_INI_PATH
echo "XDEBUG ON"

pkill -USR2 -o php-fpm
