#!/bin/sh

/usr/bin/php artisan schedule:run >> /dev/null 2>&1
