#!/bin/bash

# Author => Mohamed Attar

# This Script Is Only For Testing Changes On Server And Not For Production Purposes


cp .env.staging .env

# Start Migrating Database And Seeding Dumpy Data
echo "Migrating DB and Seeding Data"
sudo php artisan migrate --seed
echo "Done"

# Generating JWT Secret
echo "Generating JWT Secret"
sudo php artisan jwt:secret
echo "Done"

# Shortcut For Storage Directory

echo "Making Symlink For Storage"
sudo php artisan storage:link --force
echo "Done"

