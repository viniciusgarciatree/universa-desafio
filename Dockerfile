# Base image
FROM php:7.4-fpm-alpine AS base
    ENV PHP_CPPFLAGS="$PHP_CPPFLAGS -std=c++11"
    ENV COMPOSER_ALLOW_SUPERUSER 1
    ENV COMPOSER_PROCESS_TIMEOUT 900
    ENV COMPOSER_DISABLE_XDEBUG_WARN=1

    # Install dependencies
    RUN apk update && \
        apk add --no-cache \
        bash \
        openssh \
        openssl \
        zip \
        unzip \
        libzip-dev \
        sqlite \
        sqlite-dev

         
    # Install PHP extensions
    RUN docker-php-ext-install pdo \
        pdo_sqlite \
        zip

    # Configure PHP
    RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

    # Clear cache
    RUN rm -rf /var/cache/apk/*

    # Set timezone
    RUN echo "UTC" > /etc/timezone
    ARG TZ=UTC

    # Install nginx e supervisor
    RUN apk add nginx \
        supervisor \
        && mkdir -p /run/nginx

    WORKDIR /var/www

    # Copy source
    ADD . /var/www/

    # Imagem do composer
    RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

    # Create folter defaults
    RUN mkdir -p \
        data \
        bootstrap/cache
    RUN chmod -R ug+rwx data bootstrap/cache

    # Configurando DB SQLite
    RUN touch data/db.sqlite \
        && chmod -R 777 data/db.sqlite

    CMD ["php", "-S", "0.0.0.0:9000", "-t", "public/"]

    HEALTHCHECK --start-period=5s --interval=2s --timeout=5s --retries=8 CMD php || exit 1