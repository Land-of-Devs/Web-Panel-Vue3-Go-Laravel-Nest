#!/bin/bash
set -euo pipefail

if [ ! -e /tmp/installed ]; then
    apt-get update
    apt-get -y install git libicu-dev libonig-dev libzip-dev unzip locales
    apt-get clean
    rm -rf /var/lib/apt/lists/*

    locale-gen en_US.UTF-8
    localedef -f UTF-8 -i en_US en_US.UTF-8
    mkdir -p /var/run/php-fpm
    
    docker-php-ext-install intl pdo_mysql zip bcmath
    touch installed
fi

cd /project
composer config -g process-timeout 3600
composer config -g repos.packagist composer https://packagist.org
