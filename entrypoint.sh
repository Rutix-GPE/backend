#!/bin/bash
set -e

php bin/console doctrine:migrations:generate --no-interaction
php bin/console doctrine:migrations:migrate --no-interaction

