#!/bin/bash

# Author => Mohamed Attar

# This Script Is Only For Testing Changes On Server And Not For Production Purposes


cp .env.staging .env -f

# Generating App Key
echo "Generating App Key"
/usr/bin/php artisan key:gen --quiet --force
echo "Done" -e

# Start Migrating Database And Seeding Dumpy Data
echo "Migrating DB and Seeding Data"
/usr/bin/php artisan migrate:fresh --seed --quiet
echo "Done" -e

# Generating JWT Secret
echo "Generating JWT Secret"
/usr/bin/php artisan jwt:secret --quiet --force
echo "Done" -e

# Shortcut For Storage Directory

echo "Making Symlink For Storage"
/usr/bin/php artisan storage:link --force --quiet
echo "Done"

