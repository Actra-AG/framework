#!/bin/bash
ddev config --auto
ddev start
ddev add-on get ddev/ddev-phpmyadmin
ddev restart
ddev composer install
ddev import-db --file=vendor/actra/backend/db/schema.sql
ddev import-db --file=vendor/actra/backend/db/data.sql --no-drop
ddev import-db --file=local/schema.sql --no-drop
ddev import-db --file=local/data.sql --no-drop
cp local/.env.local.php .env.php