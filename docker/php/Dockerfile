FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    bash \
    git \
    curl \
    zip \
    unzip \
    icu-dev \
    oniguruma-dev \
    libzip-dev \
    libpng-dev \
    libxml2-dev \
    autoconf \
    gcc \
    g++ \
    make \
    linux-headers \
    mysql-client

RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    intl \
    zip \
    bcmath \
    opcache \
    exif \
    pcntl

RUN docker-php-ext-configure gd && docker-php-ext-install gd

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

RUN adduser -D -u 1000 -g '' app \
 && chown -R app:app /var/www/html

USER app

EXPOSE 9000
CMD ["php-fpm"]
