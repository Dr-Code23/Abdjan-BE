#!/bin/bash

# Author => Mohamed Attar

# This Script Is Only For Testing Changes On Server And Not For Production Purposes

# Optimize Composer Dependencies
echo; echo "Optimize Composer Dependencies"
/usr/bin/php composer install --optimize-autoloader --no-div
echo "Done"; echo;

# Copy .env.live into .env
echo ; echo "Copy Contents From .env.live => .env"
cp .env.live .env -f
echo "Done"; echo;

# Generating App Key
echo ;echo "Generating App Key"
/usr/bin/php artisan key:gen --quiet --force
echo "Done" ; echo;

# Generating JWT Secret
echo ;echo "Generating JWT Secret";
/usr/bin/php artisan jwt:secret --quiet --force
echo "Done"; echo;

# Shortcut For Storage Directory
echo "Making Symlink For Storage"
/usr/bin/php artisan storage:link --force --quiet
echo "Done"


# Forgot All Cached Data
echo ;echo "Forgot All Cached Data"
/usr/bin/php artisan op:cl
echo "Done" ; echo;

# Cache Config , Routes , Events To Make Links Load Faster
echo "Cache Config , Routes , Events To Make Links Load Faster"
/usr/bin/php artisan route:cache
/usr/bin/php artisan event:cache
/usr/bin/php artisan config:cache
/usr/bin/php artisan view:cache
echo "Done" ; echo;

# Generating JWT Secret
echo ;echo "Generating JWT Secret";
/usr/bin/php artisan jwt:secret --quiet --force
echo "Done"; echo;

# Shortcut For Storage Directory
echo "Making Symlink For Storage"
/usr/bin/php artisan storage:link --force --quiet
echo "Done"

