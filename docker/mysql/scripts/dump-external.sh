#!/usr/bin/bash
source ./.env

# # Removing Old Dump
rm -f ../data/dump.sql.gz

# Start container
echo 'Subindo container MYSQL...'
docker run -d --name dump-production --env MYSQL_ROOT_PASSWORD=root mysql

# Copy Dump and Adjusting
echo 'Realizando backup do banco...'
docker exec -it dump-production mysqldump --routines -h $DB_HOST -u $DB_USER --password=$DB_PASSWORD --databases bd_prisma > ../data/dump.sql --no-tablespaces

# Stop container
echo 'Parando container MYSQL...'
docker stop dump-production

# Remove container
echo 'Removendo container MYSQL...'
docker rm dump-production

# Ajusting dump
sed -i "/Using a password on the command line interface can be insecure/d" ../data/dump.sql
sed -i "/Warning: A partial dump from a server/d" ../data/dump.sql

# Restoring
docker exec -i $CONTAINER_NAME sh -c "exec mysql -u $MYSQL_ROOT_USER --password=$MYSQL_ROOT_PASSWORD" < ../data/dump.sql

# # Gzip Dump
gzip ../data/dump.sql