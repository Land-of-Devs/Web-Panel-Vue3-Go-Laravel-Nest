#!/bin/bash

read -p "Email: " email
read -p "Username: " username
read -p "Password: " password

curl -v -X POST -H "Content-Type: application/json" \
  -d '{"username": "'$username'", "password": "'$password'", "email": "'$email'"}' \
  http://localhost/api/user/access/signup

echo
