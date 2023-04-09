#!/bin/bash

# Author => Mohamed Attar

# This Script Is Only For Testing Changes On Server And Not For Production Purposes


cp .env.staging .env

# Generating App Key
echo "Generating App Key"
/usr/bin/php artisan key:gen --quiet --force
echo "Done"

# Start Migrating Database And Seeding Dumpy Data
echo "Migrating DB and Seeding Data"
/usr/bin/php artisan migrate:fresh --seed --quiet
echo "Done"

# Generating JWT Secret
echo "Generating JWT Secret"
sudo php artisan jwt:secret --quiet --force
echo "Done"

# Shortcut For Storage Directory

echo "Making Symlink For Storage"
sudo php artisan storage:link --force --quiet
echo "Done"

