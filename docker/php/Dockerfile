FROM php:latest

RUN apt-get update && apt-get install -y git zip

RUN pecl install xdebug-2.6.0 \
  && docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer >/usr/local/bin/composer-setup.php \
  && php /usr/local/bin/composer-setup.php \
      --quiet \
      --install-dir=/usr/local/bin \
      --filename=composer

WORKDIR /named-sprintf
