#!/bin/bash

session=$(curl http://localhost/api/admin/auth/test-auth -s --cookie-jar - | awk '/session/ { print $7 }')
exit
curl -v -X POST -H "Content-Type: application/json" \
  -d '{"email": "'$email'", "password": "'$password'"}' \
  http://localhost/api/user/access/signin

echo
