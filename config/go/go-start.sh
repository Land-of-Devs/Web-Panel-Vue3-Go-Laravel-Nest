#!/bin/sh

if ! which gow &>/dev/null; then
  go install github.com/mitranim/gow@latest
fi

cd /project
#go mod download
gow run .
