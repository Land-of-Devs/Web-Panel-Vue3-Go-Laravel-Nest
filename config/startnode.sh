#!/bin/sh

#Perms
uid=`stat -c '%u' .`
gid=`stat -c '%g' .`

sed -i -e "s/^node:x:[0-9]*:[0-9]*:/node:x:${uid}:${gid}:/" /etc/passwd
chown ${uid}:${gid} /home/node

# Install and run
exec su node -c "id && npm install && npm run dev"
