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

# Start cron service
service cron start

# Finish setup; original entrypoint will be executed by wrapper
log "Environment setup completed successfully!"