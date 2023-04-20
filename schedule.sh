#!/bin/sh

/usr/bin/php artisan schedule:run >> /dev/null 2>&1

current_date_time=$(date)
echo "Last Time Checked: $current_date_time"
