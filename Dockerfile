FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    apt-utils git libpq-dev libzip-dev zip unzip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

RUN docker-php-ext-install pdo pdo_pgsql

COPY . /var/www/html

COPY entrypoint.sh /usr/local/bin/

RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]

EXPOSE 9000
