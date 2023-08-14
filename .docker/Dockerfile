ARG NGINX_VERSION=1.17

# -----------------------------------------------------------------------------
# PHP - Common
# -----------------------------------------------------------------------------

# Build PHP application image
FROM thecodingmachine/php:8.1-v4-fpm-node12 AS armaforces_web_php

USER root

# PHP Extensions
ENV \
    PHP_EXTENSION_INTL=1

# PHP ini configuration
# https://github.com/dunglas/symfony-docker/blob/380dc902595e0575c07f68f84e3266745e8f8100/docker/php/conf.d/symfony.prod.ini
ENV \
    PHP_INI_MEMORY_LIMIT=256M \
    PHP_INI_APC__ENABLE_CLI=1 \
    PHP_INI_DATE__TIMEZONE=Europe/Warsaw \
    PHP_INI_SESSION__AUTO_START=Off \
    PHP_INI_SHORT_OPEN_TAG=Off \
    PHP_INI_EXPOSE_PHP=Off

WORKDIR /www/app
RUN mkdir -p var/cache var/log public/build
RUN chown docker:docker -R /www/app
USER docker

COPY --link package.json package-lock.json ./
RUN npm install

# prevent the reinstallation of vendors at every changes in the source code
COPY --link composer.* symfony.* ./
RUN set -eux; \
	composer install --no-cache --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress

# copy sources
COPY --link bin bin/
COPY config config/
COPY migrations migrations/
COPY public public/
COPY src src/
COPY templates templates/
COPY translations translations/
COPY .env ./

COPY --link assets assets/
COPY webpack.config.js ./

USER root
RUN chmod +x bin/console
USER docker


# -----------------------------------------------------------------------------
# PHP - Prod
# -----------------------------------------------------------------------------
FROM armaforces_web_php AS armaforces_web_php_prod

ENV \
    TEMPLATE_PHP_INI=production \
    \
    PHP_INI_OPCACHE__INTERNED_STRINGS_BUFFER=16 \
    PHP_INI_OPCACHE__MAX_ACCELERATED_FILES=20000 \
    PHP_INI_OPCACHE__MEMORY_CONSUMPTION=256 \
    PHP_INI_OPCACHE__VALIDATE_TIMESTAMPS=0 \
    PHP_INI_REALPATH_CACHE_SIZE=4096K \
    PHP_INI_REALPATH_CACHE_TTL=600 \
    PHP_INI_OPCACHE__PRELOAD_USER=www-data \
    PHP_INI_OPCACHE__PRELOAD=/www/app/config/preload.php

COPY --chown=docker:docker .docker/php/startup.sh /etc/container/startup.sh
RUN chmod +x /etc/container/startup.sh

RUN npm run build
RUN composer dump-autoload --classmap-authoritative --no-dev; \
	composer run-script --no-dev post-install-cmd;

# -----------------------------------------------------------------------------
# PHP - Dev
# -----------------------------------------------------------------------------
FROM armaforces_web_php AS armaforces_web_php_dev

ENV \
    PHP_EXTENSION_XDEBUG=1 \
    PHP_INI_XDEBUG__MODE=debug,coverage \
    PHP_INI_XDEBUG__CLIENT_HOST=host.docker.internal \
    PHP_INI_XDEBUG__CLIENT_PORT=9003 \
    PHP_INI_XDEBUG__START_WITH_REQUEST=yes \
    PHP_INI_XDEBUG__IDEKEY=armaforces-web \
    PHP_INI_XDEBUG__LOG_LEVEL=0

COPY --chown=docker:docker .docker/php/startup.dev.sh /etc/container/startup.sh
RUN chmod +x /etc/container/startup.sh

COPY tests tests/
COPY .env.test phpunit.xml.dist .php-cs-fixer.dist.php phpstan.neon.dist ./

# -----------------------------------------------------------------------------
# NGINX
# -----------------------------------------------------------------------------
FROM nginx:${NGINX_VERSION}-alpine AS armaforces_web_nginx

COPY .docker/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf.template

WORKDIR /www/app

COPY --from=armaforces_web_php /www/app/public public/

COPY .docker/nginx/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]
CMD ["nginx", "-g", "daemon off;"]
