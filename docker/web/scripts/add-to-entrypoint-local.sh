#!/bin/bash

# Function for logging messages
log() {
    echo "$(date +'%Y-%m-%d %H:%M:%S') - $1"
}

# Log script start
log "Starting the entrypoint script.."

# Wait for database TCP socket instead of requiring credentials
if [ ! -z "$DB_HOST" ]; then
    DB_WAIT_PORT=${DB_PORT:-3306}
    until (echo > /dev/tcp/${DB_HOST}/${DB_WAIT_PORT}) >/dev/null 2>&1; do
        >&2 log "Waiting for the database to become available..."
        sleep 10
    done
    >&2 echo "Database is up and running!"
fi

# Wait until the specified directory is available
while [ ! -d "${WORKDIR}" ]; do
    log "Waiting for the specified volume to be mounted..."
    sleep 10  # Wait for 10 seconds before checking again
done

# Check if the vendor directory does not exist and install if necessary
if [ ! -d "${WORKDIR}/api/vendor" ]; then
    log "Installing dependencies using Composer..."
    composer install --working-dir=${WORKDIR}/api
else
    log "Running composer dumpautoload..."
    composer dumpautoload --working-dir=${WORKDIR}/api
fi

# Adjust permissions
log "Define owner and permissions..."
chown www-data:www-data -R "${WORKDIR}"

# Start cron service
service cron start

# Finish setup; original entrypoint will be executed by wrapper
log "Environment setup completed successfully!"