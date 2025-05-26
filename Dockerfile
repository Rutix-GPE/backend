FROM php:8.3-apache

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN apt-get update && apt-get install -y libicu-dev git unzip && docker-php-ext-install intl
RUN apt-get install -y netcat-traditional

COPY wait-for-it.sh /usr/local/bin/wait-for-it.sh
RUN chmod +x /usr/local/bin/wait-for-it.sh

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN curl -sS https://get.symfony.com/cli/installer | bash && \
    mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

WORKDIR /var/www/html
COPY . .

RUN git config --global --add safe.directory /var/www/html

# On supprime composer install ici car il sera exécuté dans docker-compose
# RUN composer install --optimize-autoloader

EXPOSE 80

CMD ["apache2-foreground"]
