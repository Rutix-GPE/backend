# Utiliser l'image officielle PHP avec Apache
FROM php:8.3-apache


# Installer les extensions PHP nécessaires
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Installer les extensions nécessaires pour Symfony
RUN apt-get update && apt-get install -y libicu-dev git unzip && \
    docker-php-ext-install intl

# Copier le script wait-for-it.sh
COPY wait-for-it.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/wait-for-it.sh


# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# install symfony

RUN curl -sS https://get.symfony.com/cli/installer | bash

RUN  mv /root/.symfony5/bin/symfony /usr/local/bin/symfony


# Définir le répertoire de travail pour les commandes suivantes
WORKDIR /var/www/html

# Copier le code source de l'application dans le conteneur
COPY . /var/www/html/



# Donner les permissions et installer les dépendances
RUN chown -R www-data:www-data /var/www/html && \
    composer install --optimize-autoloader


# Exposer le port 80
EXPOSE 80

# Attendre que MySQL soit prêt avant d'exécuter les commandes Symfony
CMD ["wait-for-it.sh", "db:3306", "--", "apache2-foreground"]
