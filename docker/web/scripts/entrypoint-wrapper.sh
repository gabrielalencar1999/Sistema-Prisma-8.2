#!/usr/bin/env bash
set -e

# Normalize Windows line endings if present
sed -i 's/\r$//' /usr/local/bin/docker-php-entrypoint || true
if [ -f /tmp/add-to-entrypoint.sh ]; then
  sed -i 's/\r$//' /tmp/add-to-entrypoint.sh || true
  /bin/bash /tmp/add-to-entrypoint.sh || true
fi

# Start Apache explicitly with envvars loaded
set +e
. /etc/apache2/envvars 2>/dev/null || true
set -e
exec /usr/sbin/apache2ctl -D FOREGROUND



