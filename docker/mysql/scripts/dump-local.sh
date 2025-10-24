#!/usr/bin/bash
source ../../../.env

# Removing Old Dump
rm -f ../data/dump.sql.gz

# Start container
docker container start $APP_NAME-$DB_HOST

# Copy Dump and Adjusting
docker exec -it $APP_NAME-$DB_HOST mysqldump --routines -u $MYSQL_ROOT_USER --password=$MYSQL_ROOT_PASSWORD --databases bd_G000001 bd_gestorpet minhaos_cep > ../data/dump.sql --no-tablespaces

# Ajusting dump
sed -i "/Using a password on the command line interface can be insecure/d" ../data/dump.sql

# Gzip Dump
gzip ../data/dump.sql