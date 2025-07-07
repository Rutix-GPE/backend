#!/bin/bash

# 🕒 Attendre que la DB principale (rutix_db) soit prête
echo "Waiting for main database (rutix_db)..."
while ! nc -z db 3306; do
  echo "Waiting for db:3306..."
  sleep 1
done
echo "Main database is ready."

# 🕒 Vérifier aussi la DB de test (optionnel, car sur le même MySQL, mais bon)
# (pas indispensable ici car c’est le même MySQL)

# 🏗️ Générer et appliquer les migrations sur la base principale
echo "Running migrations on rutix_db..."
php bin/console doctrine:migrations:migrate --no-interaction

# 🏗️ Appliquer les migrations sur la base de test
echo "Running migrations on rutix_test_db..."
DATABASE_URL="mysql://test_user:test_password@db:3306/rutix_test_db" php bin/console doctrine:migrations:migrate --no-interaction

# 🚀 Démarrer le serveur PHP
echo "Starting PHP built-in server..."
php -S 0.0.0.0:80 -t public
