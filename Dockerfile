FROM php:8.3-apache
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN apt-get update && apt-get install -y libicu-dev git unzip && \
    docker-php-ext-install intl

RUN apt install netcat-traditional
COPY wait-for-it.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/wait-for-it.sh

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN curl -sS https://get.symfony.com/cli/installer | bash

RUN  mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

WORKDIR /var/www/html
COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html && \
    composer install --optimize-autoloader

EXPOSE 80
EXPOSE 80

CMD ["php","-S","0.0.0.0:80","-t", "public"]
