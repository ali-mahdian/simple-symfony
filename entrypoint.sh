#!/bin/bash
set -e

composer install

php bin/console doctrine:schema:update --force

php-fpm
