#!/bin/sh

if ! which gow &>/dev/null; then
  go install github.com/mitranim/gow@latest
fi

cd /project
exec gow run .
