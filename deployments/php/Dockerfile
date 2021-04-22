FROM php:8.0.2-fpm-alpine3.13

RUN apk upgrade --update && apk add --no-cache \
    libpng-dev \
    libzip-dev \
    libwebp-dev \
    libjpeg-turbo-dev \
    libpng-dev libxpm-dev \
    freetype-dev \
    icu-dev \
    libpq \
    imagemagick \
    build-base \
    rabbitmq-c \
    rabbitmq-c-dev \
    zlib-dev \
    php7-dev \
    bash \
    curl

RUN docker-php-ext-configure gd && \
    docker-php-ext-install zip gd && \
    docker-php-ext-install mysqli pdo pdo_mysql && \
    docker-php-ext-configure intl && \
    docker-php-ext-install intl

RUN pecl install -o -f redis && docker-php-ext-enable redis && rm -rf /tmp/pear

RUN if [ $XDEBUG_MODE = "true" ]; then \
        pecl install xdebug-2.7.1 && \
        docker-php-ext-enable xdebug && \
        echo xdebug.remote_enable=1 >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
        echo xdebug.remote_port=9000 >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
        echo xdebug.remote_autostart=1 >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
        echo xdebug.remote_connect_back=0 >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
        echo xdebug.idekey=PHPSTORM >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
        echo xdebug.remote_host=192.168.220.1 >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    fi

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN mkdir /.composer
RUN chown -R www-data /.composer
RUN chmod -R 777 /.composer
