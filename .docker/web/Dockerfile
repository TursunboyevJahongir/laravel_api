FROM php:8.1.4-fpm-alpine3.14

LABEL maintainer="Jahongir Tursunboyev"

WORKDIR /var/www

RUN apk update && apk add --no-cache \
    zip \
    unzip \
    curl \
    git \
    dos2unix \
    libpng-dev \
    libzip-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    zstd-dev \
    supervisor \
    nginx \
    nano \
    htop \
    coreutils \
    busybox-suid \
    openrc \
    $PHPIZE_DEPS

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

RUN docker-php-ext-install \
    pcntl \
    bcmath \
    mysqli \
    pdo_mysql

RUN pecl install zip igbinary redis
RUN docker-php-ext-enable zip
RUN docker-php-ext-enable igbinary
RUN docker-php-ext-enable redis

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN addgroup -g 1000 www
RUN adduser -u 1000 -G www -h /home/www -D www

COPY --chown=1000:1000 . /var/www
RUN chown -R www:www /var/lib/nginx
RUN chmod -R 755 /var/www/storage /var/www/bootstrap
COPY ./.docker/web.dev/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./.docker/web/supervisord.conf /etc/supervisord.conf
COPY ./.docker/web/nginx.conf /etc/nginx/nginx.conf
COPY ./.docker/web/app.conf /etc/nginx/conf.d/default.conf
COPY ./.docker/web/container-start /usr/local/bin/container-start

RUN touch /var/log/cron.log
ADD ./.docker/web/crontab.txt /crontab.txt
RUN /usr/bin/crontab /crontab.txt
RUN mkdir /var/log/supervisor
RUN chmod +x /usr/local/bin/container-start

RUN php /usr/local/bin/composer install --no-dev
RUN php artisan optimize

EXPOSE 443

ENTRYPOINT ["container-start"]

RUN docker-php-ext-install opcache
COPY ./.docker/web/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
