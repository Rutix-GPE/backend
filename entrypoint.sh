#!/bin/bash
while ! nc -z db 3306; do sleep 1; done 
php bin/console make:migration --no-interaction

php -S 0.0.0.0:80 -t public