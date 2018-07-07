FROM php:7.0-alpine

RUN apk add --no-cache --virtual .phpize-deps \
      $PHPIZE_DEPS \
    && apk add --no-cache \
        git \
        zlib-dev \
    && docker-php-ext-install \
        bcmath \
        zip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del --no-cache .phpize-deps

RUN curl -sS https://getcomposer.org/installer >/usr/local/bin/composer-setup.php \
  && php /usr/local/bin/composer-setup.php \
      --quiet \
      --install-dir=/usr/local/bin \
      --filename=composer

WORKDIR /named-sprintf
