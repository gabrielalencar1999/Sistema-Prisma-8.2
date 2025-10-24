#!/usr/bin/bash
source ../../../.env

#Restoring
gzip -d ../data/dump.sql.gz
docker exec -i $APP_NAME-$DB_HOST sh -c "exec mysql -u $MYSQL_ROOT_USER --password=$MYSQL_ROOT_PASSWORD" < ../data/dump.sql
gzip ../data/dump.sql