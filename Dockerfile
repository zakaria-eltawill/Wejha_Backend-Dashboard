# syntax=docker/dockerfile:1

#######################################
# Stage 1: PHP dependencies (Composer)
#######################################
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./

# Install PHP deps without running any app scripts (artisan isn't available yet -
# the full app code isn't copied into this stage). --no-scripts skips composer.json's
# post-autoload-dump hooks (package:discover, filament:upgrade), which need artisan;
# those run for real later via docker/entrypoint.sh once the whole app is in the image.
#
# --ignore-platform-reqs is needed because the composer:2 image's bundled PHP (8.5,
# and missing ext-intl/ext-gd/ext-calendar) doesn't match this app's target runtime
# (PHP 8.3 with those extensions installed, see the runtime stage below) — several
# locked packages (phpoffice/phpspreadsheet, openspout/openspout) even cap below
# PHP 8.5. This stage only downloads/resolves packages into vendor/ — no package code
# runs here (--no-scripts) — so composer's platform check is checking the wrong PHP
# entirely; what actually matters is that the runtime stage's PHP 8.3 + extensions
# satisfies every package's requirements, which it does.
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader \
    --ignore-platform-reqs

#######################################
# Stage 2: Frontend assets (Vite)
#######################################
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json* ./
RUN npm ci

COPY resources/ resources/
COPY vite.config.* ./
COPY public/ public/

RUN npm run build

#######################################
# Stage 3: Runtime (PHP-FPM + Nginx, one container via supervisord)
#######################################
FROM php:8.3-fpm-alpine AS runtime

# System packages needed at runtime + the -dev packages needed only to compile PHP
# extensions (removed again in the same layer to keep the final image lean).
RUN apk add --no-cache \
        bash \
        curl \
        nginx \
        postgresql-client \
        libpng \
        libjpeg-turbo \
        freetype \
        libzip \
        icu-libs \
        icu-data-full \
        supervisor \
    && apk add --no-cache --virtual .build-deps \
        postgresql-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        libzip-dev \
        icu-dev \
        $PHPIZE_DEPS \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_pgsql \
        pgsql \
        gd \
        zip \
        intl \
        bcmath \
        calendar \
        opcache \
        pcntl \
    && apk del .build-deps \
    && rm -rf /var/cache/apk/*

# Production PHP/OPcache tuning.
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/php/production.ini /usr/local/etc/php/conf.d/zz-production.ini
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisord.conf

WORKDIR /var/www/html

# Non-root user everything (nginx, php-fpm, supervisord) runs as. Nginx listens on an
# unprivileged port (8080, see docker/nginx/default.conf) specifically so it never
# needs root/CAP_NET_BIND_SERVICE — the compose file maps host port 80 -> 8080.
RUN addgroup -g 1000 wejha \
    && adduser -G wejha -u 1000 -D -h /var/www/html wejha \
    && mkdir -p /var/lib/nginx/tmp/client_body /var/lib/nginx/tmp/proxy \
        /var/lib/nginx/tmp/fastcgi /var/lib/nginx/tmp/uwsgi /var/lib/nginx/tmp/scgi \
        /var/log/supervisor \
    && chown -R wejha:wejha /var/lib/nginx /var/log/nginx /var/log/supervisor /run

COPY --chown=wejha:wejha . .
COPY --from=vendor --chown=wejha:wejha /app/vendor ./vendor
COPY --from=frontend --chown=wejha:wejha /app/public/build ./public/build

RUN mkdir -p storage/framework/{sessions,views,cache,testing} \
        storage/logs \
        storage/app/public \
        storage/app/dompdf-temp \
        storage/fonts \
        bootstrap/cache \
    && chown -R wejha:wejha storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

USER wejha

EXPOSE 8080

HEALTHCHECK --interval=30s --timeout=5s --start-period=30s --retries=3 \
    CMD curl -fsS http://127.0.0.1:8080/up > /dev/null || exit 1

ENTRYPOINT ["entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisord.conf", "-n"]
