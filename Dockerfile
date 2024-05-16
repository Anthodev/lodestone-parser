FROM php:8.3-alpine AS php_base

WORKDIR /app

VOLUME /app/var/

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN apk add --no-cache \
	acl \
	file \
	gettext \
	git \
;

RUN set -eux; \
	install-php-extensions \
		@composer \
		apcu \
		intl \
		opcache \
	;

ENV COMPOSER_ALLOW_SUPERUSER=1

# Dev FrankenPHP image
FROM php_base AS php_dev

ENV XDEBUG_MODE=debug

VOLUME /app/var/

RUN apk add --no-cache \
		curl \
	;

RUN set -eux; \
	install-php-extensions \
		xdebug \
	;

FROM php_base AS php_prod

COPY --link docker/conf.d/app.prod.ini $PHP_INI_DIR/conf.d/

COPY --link composer.* ./
RUN set -eux; \
	composer install --no-cache --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress

# copy sources
COPY --link . ./
RUN rm -Rf docker/

RUN set -eux; \
	composer dump-autoload --classmap-authoritative --no-dev; \
	composer dump-env prod; \
	composer run-script --no-dev post-install-cmd;
