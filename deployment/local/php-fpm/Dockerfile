FROM php:8.3-fpm-alpine

RUN apk update && apk add --no-cache \
    libzip-dev \
    zip \
    unzip \
    bash \
    mysql-client \
    && docker-php-ext-install pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

COPY . .

RUN chown -R www-data:www-data /var/www/html

RUN ln -s /var/www/html/vendor/bin/phinx /usr/local/bin/phinx

EXPOSE 9000

CMD php-fpm
