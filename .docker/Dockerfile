# the different stages of this Dockerfile are meant to be built into separate images
# https://docs.docker.com/compose/compose-file/#target

ARG PHP_VERSION=8.0
ARG NGINX_VERSION=1.17

# Build assets in disposable node image
FROM node:11-alpine as armaforces_web_assets

WORKDIR /tmp/app_build

COPY package.json package-lock.json webpack.config.js ./
RUN npm install

WORKDIR /tmp/app_build/assets/

COPY assets ./
RUN npm run build:ci


# Build PHP application image
FROM php:${PHP_VERSION}-fpm-alpine AS armaforces_web_php

RUN apk add --no-cache \
        acl \
        fcgi \
        file \
        gettext \
        git \
    ;

ARG APCU_VERSION=5.1.18
RUN set -eux; \
    apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        icu-dev \
        libzip-dev \
        zlib-dev \
        postgresql-dev \
    ; \
    \
    docker-php-ext-configure zip; \
    docker-php-ext-install -j$(nproc) \
        intl \
        zip \
        pdo_pgsql \
    ; \
    pecl install \
        apcu-${APCU_VERSION} \
    ; \
    pecl clear-cache; \
    docker-php-ext-enable \
        apcu \
        opcache \
    ; \
    \
    runDeps="$( \
        scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
            | tr ',' '\n' \
            | sort -u \
            | awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
    )"; \
    apk add --no-cache --virtual .api-phpexts-rundeps $runDeps; \
    \
    apk del .build-deps \
;

COPY --from=composer/composer:2-bin /composer /usr/bin/composer
RUN ln -s $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini
COPY .docker/php/conf.d/armaforces-web.ini $PHP_INI_DIR/conf.d/armaforces-web.ini

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"

WORKDIR /www/app

# build for production
ARG APP_ENV=prod

# prevent the reinstallation of vendors at every changes in the source code
COPY composer.json composer.lock symfony.lock .env ./
RUN set -eux; \
    composer install --prefer-dist --no-dev --no-scripts --no-progress --no-suggest; \
    composer clear-cache

# copy only specifically what we need
COPY bin bin/
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
VOLUME /www/app/var

# copy assets manifests
COPY --from=armaforces_web_assets /tmp/app_build/public/build/ public/build/

COPY .docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
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

ARG XDEBUG_VERSION=3.1.1
RUN set -eux; \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS; \
    pecl install xdebug-$XDEBUG_VERSION; \
    docker-php-ext-enable xdebug; \
    apk del .build-deps

RUN apk add --no-cache \
        nodejs \
        npm

ENV XDEBUG_INI_PATH=$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini
COPY .docker/php/conf.d/docker-php-ext-xdebug.ini $XDEBUG_INI_PATH

COPY .docker/php/scripts/xon.sh /usr/bin/xon
COPY .docker/php/scripts/xoff.sh /usr/bin/xoff
RUN chmod +x /usr/bin/xon /usr/bin/xoff
