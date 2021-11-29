#!/bin/sh

#Perms
uid=`stat -c '%u' /app`
gid=`stat -c '%g' /app`

sed -i 's/;extension=pdo_pgsql/extension=pdo_pgsql/g; s/;extension=pgsql/extension=pgsql/g;' /opt/bitnami/php/etc/php.ini*
sed -i -e "s/^bitnami:x:[0-9]*:[0-9]*:/bitnami:x:${uid}:${gid}:/" /etc/passwd

su bitnami -c "id && bash /app-entrypoint.sh php artisan serve --host=0.0.0.0 --port=3000"
#exec /app-entrypoint.sh php artisan serve --host=0.0.0.0 --port=3000
