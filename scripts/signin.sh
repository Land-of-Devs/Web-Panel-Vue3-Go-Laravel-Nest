#!/bin/bash

read -p "Email: " email
read -p "Password: " password

curl -v -X POST -H "Content-Type: application/json" \
  -d '{"email": "'$email'", "password": "'$password'"}' \
  http://localhost/api/user/access/signin

echo
