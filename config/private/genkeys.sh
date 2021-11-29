#!/bin/bash
set -euo pipefail

if ! which openssl; then
  echo "OpenSSL is required to use this"
  exit 1
fi

TARGET=${1:-dev}

genkeys() {
  mkdir -p "${1}"
  openssl genrsa -des3 -out "${1}/private.pem" 4096
  openssl rsa -in "${1}/private.pem" -outform PEM -pubout -out "${1}/public.pem"
}

genkeys "$TARGET"
