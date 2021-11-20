#!/bin/sh

if [ ! -e /tmp/installed ]; then
    sudo sed -i 's/;extension=pdo_pgsql/extension=pdo_pgsql/g; s/;extension=pgsql/extension=pgsql/g;' /opt/bitnami/php/etc/php.ini*

    touch installed
fi

exec /app-entrypoint.sh php artisan serve --host=0.0.0.0 --port=3000
