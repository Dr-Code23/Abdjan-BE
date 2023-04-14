#!/bin/bash

# Author => Mohamed Attar

# This Script Is Only For Testing Changes On Server And Not For Production Purposes

# Running Composer Update
echo; echo "Running Composer Update"
/usr/bin/php composer.phar update
echo "Done"; echo;

# Copy .env.staging into .env
echo ; echo "Copy Contents From .env.stating => .env"
cp .env.staging .env -f
echo "Done"; echo;

# Generating App Key
echo ;echo "Generating App Key"
/usr/bin/php artisan key:gen --quiet --force
echo "Done" ; echo;

# Start Migrating Database And Seeding Dumpy Data
echo "Migrating DB and Seeding Data"
/usr/bin/php artisan migrate:fresh --seed --quiet
echo "Done" ; echo;

# Generating JWT Secret
echo ;echo "Generating JWT Secret";
/usr/bin/php artisan jwt:secret --quiet --force
echo "Done"; echo;

# Shortcut For Storage Directory
echo "Making Symlink For Storage"
/usr/bin/php artisan storage:link --force --quiet
echo "Done"

