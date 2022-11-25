FROM php:8.1-apache

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite headers

COPY . /var/www/html/

RUN apt-get update -y && apt-get install -y zip libzip-dev
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install zip

RUN composer update --lock && composer install && php bin/console doctrine:database:create && php bin/console doctrine:migrations:migrate --no-interaction

RUN chmod -R 777 var/

