#!/bin/bash

# Replace 'my-container' with the name of your Docker container, and 'my-database' with the name of your database
docker exec mariadb_db bash -c "/usr/bin/mysqldump -u root -prootpassword library > /docker-entrypoint-initdb.d/backup.sql"

# Now you can stop the container
docker stop mariadb_db
docker stop php_web