FROM composer:2.3.5 AS build

WORKDIR /build

COPY composer.* symfony.lock ./

RUN composer install --no-dev --no-scripts --no-autoloader --ignore-platform-reqs

COPY . .

RUN composer dump-autoload --optimize

FROM php:8.1.6-fpm-alpine3.15 AS production

RUN apk add --no-cache --virtual .production-deps \
    nginx icu libpq rabbitmq-c

RUN set -xe \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    icu-dev libpq-dev rabbitmq-c-dev \
    && pecl install -f amqp-1.11.0 apcu-5.1.21 \
    && docker-php-source extract \
    && docker-php-ext-configure intl \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install -j$(nproc) intl pdo_pgsql pgsql \
    && docker-php-ext-enable amqp apcu \
    && docker-php-source delete \
    && pecl clear-cache \
    && rm -rf /tmp/* \
    && apk del .build-deps

COPY docker/etc /etc
COPY docker/etc /usr/local/etc

ARG S6_OVERLAY_VERSION=v3.1.0.1
RUN S6_OVERLAY_RELEASE=https://github.com/just-containers/s6-overlay/releases/download/${S6_OVERLAY_VERSION} \
    && curl -sSL $S6_OVERLAY_RELEASE/s6-overlay-noarch.tar.xz | tar Jxpf - -C / \
    && curl -sSL $S6_OVERLAY_RELEASE/s6-overlay-x86_64.tar.xz | tar Jxpf - -C / \
    && chmod -R +x /etc/cont-init.d /etc/services.d
ENV S6_CMD_WAIT_FOR_SERVICES_MAXTIME=60000

COPY --from=build --chown=www-data:www-data /build /var/www/stargaze

EXPOSE 80
WORKDIR /var/www/stargaze
ENTRYPOINT ["/init"]

FROM production AS development

ARG SYMFONY_CLI_VERSION=5.4.8
RUN SYMFONY_CLI_RELEASE=https://github.com/symfony-cli/symfony-cli/releases/download/v${SYMFONY_CLI_VERSION} \
    && SYMFONY_CLI_PACKAGE=symfony-cli_${SYMFONY_CLI_VERSION}_x86_64.apk \
    && wget -qP /tmp $SYMFONY_CLI_RELEASE/$SYMFONY_CLI_PACKAGE \
    && apk add --allow-untrusted /tmp/$SYMFONY_CLI_PACKAGE \
    && rm -f /tmp/$SYMFONY_CLI_PACKAGE

RUN set -xe \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install -f xdebug-3.1.4 \
    && docker-php-source extract \
    && docker-php-ext-configure pcntl \
    && docker-php-ext-configure posix \
    && docker-php-ext-install -j$(nproc) pcntl posix \
    && docker-php-ext-enable xdebug \
    && docker-php-source delete \
    && pecl clear-cache \
    && apk del .build-deps

ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=build /usr/bin/composer /usr/local/bin/composer
