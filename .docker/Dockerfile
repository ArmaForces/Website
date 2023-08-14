# the different stages of this Dockerfile are meant to be built into separate images
# https://docs.docker.com/compose/compose-file/#target

ARG PHP_VERSION=8.1
ARG NGINX_VERSION=1.17

# Build assets in disposable node image
FROM node:11-alpine as armaforces_web_assets

WORKDIR /tmp/app_build

COPY --link package.json package-lock.json webpack.config.js ./
RUN npm install

WORKDIR /tmp/app_build/assets/

COPY --link assets ./
RUN npm run build:ci


# Build PHP application image
FROM php:${PHP_VERSION}-fpm-alpine AS armaforces_web_php

# build for production
ENV APP_ENV=prod

WORKDIR /www/app

# php extensions installer: https://github.com/mlocati/docker-php-extension-installer
COPY --from=mlocati/php-extension-installer --link /usr/bin/install-php-extensions /usr/local/bin/

# persistent / runtime deps
RUN apk add --no-cache \
        acl \
        fcgi \
        file \
        gettext \
        git \
    ;

RUN set -eux; \
    install-php-extensions \
        intl \
        zip \
        apcu \
        opcache \
        pdo_mysql \
    ;

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY --link .docker/php/conf.d/armaforces-web.ini $PHP_INI_DIR/conf.d/
COPY --link .docker/php/conf.d/armaforces-web.prod.ini $PHP_INI_DIR/conf.d/

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"

COPY --from=composer/composer:2-bin --link /composer /usr/bin/composer

# prevent the reinstallation of vendors at every changes in the source code
COPY composer.json composer.lock symfony.lock .env ./
RUN set -eux; \
    composer install --prefer-dist --no-dev --no-scripts --no-progress; \
    composer clear-cache

# copy only specifically what we need
COPY --link bin bin/
COPY config config/
COPY migrations migrations/
COPY public public/
COPY src src/
COPY templates templates/
COPY translations translations/

RUN set -eux; \
    mkdir -p var/cache var/log; \
    composer dump-autoload --classmap-authoritative --no-dev; \
    composer run-script --no-dev post-install-cmd; \
    chmod +x bin/console; sync

# copy assets manifests
COPY --from=armaforces_web_assets /tmp/app_build/public/build/ public/build/

COPY --link .docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

# .env is copied again as COPY needs to copy at least one file
COPY .env VERSIO[N] ./

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]


FROM nginx:${NGINX_VERSION}-alpine AS armaforces_web_nginx

COPY .docker/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf.template

WORKDIR /www/app

COPY --from=armaforces_web_php /www/app/public public/

COPY .docker/nginx/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]
CMD ["nginx", "-g", "daemon off;"]


FROM armaforces_web_php AS armaforces_web_php_dev

ENV APP_ENV=dev
#VOLUME /www/app/var/

RUN rm $PHP_INI_DIR/conf.d/app.prod.ini; \
	mv "$PHP_INI_DIR/php.ini" "$PHP_INI_DIR/php.ini-production"; \
	mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

RUN set -eux; \
	install-php-extensions xdebug

RUN apk add --no-cache \
        nodejs \
        npm

ENV XDEBUG_INI_PATH=$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini
COPY --link .docker/php/conf.d/docker-php-ext-xdebug.ini $XDEBUG_INI_PATH

COPY --link .docker/php/scripts/xon.sh /usr/bin/xon
COPY --link .docker/php/scripts/xoff.sh /usr/bin/xoff
RUN chmod +x /usr/bin/xon /usr/bin/xoff
