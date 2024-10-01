# Utiliser l'image officielle PHP avec Apache
FROM php:8.3-apache

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Installer les extensions nécessaires pour Symfony
RUN apt-get update && apt-get install -y libicu-dev git unzip && \
    docker-php-ext-install intl

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail pour les commandes suivantes
WORKDIR /var/www/html

# Copier le code source de l'application dans le conteneur
COPY . /var/www/html/

# Donner les permissions avant d'installer les dépendances
RUN chown -R www-data:www-data /var/www/html && \
    composer install --no-dev --optimize-autoloader

# Configurer les permissions des fichiers
RUN chown -R www-data:www-data /var/www/html/

# Exposer le port 80
EXPOSE 80
# Donner les permissions à www-data avant d'exécuter Composer
RUN chown -R www-data:www-data /var/www/html && \
    composer install --optimize-autoloader
