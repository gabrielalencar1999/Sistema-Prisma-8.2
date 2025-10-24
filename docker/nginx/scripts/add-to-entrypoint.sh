#!/usr/bin/env bash

# Function for logging messages
log() {
    echo "$(date +'%Y-%m-%d %H:%M:%S') - $1"
}

until curl -s -o /dev/null "http://web"; do
    log "Container web is unavailable - waiting..."
    sleep 5
done

log "Application is up!"

exec "$@"