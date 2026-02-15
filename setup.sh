#!/bin/bash
git submodule update --init
ddev config --auto
ddev start
ddev add-on get ddev/ddev-phpmyadmin
ddev restart
ddev import-db --file=local/schema.sql
ddev import-db --file=local/data.sql --no-drop
mkdir current/files
cp local/LocalEnvSettings.php.src current/site/settings/EnvSettings.php