#!/bin/bash

# Mengganti port default Apache (80) dengan port dinamis dari Render ($PORT atau 10000)
sed -i "s/80/${PORT:-10000}/g" /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf

# Optimasi cache Laravel & jalankan migrasi database otomatis
php artisan config:cache
php artisan route:cache
php artisan migrate --force

# Jalankan Apache di foreground
apache2-foreground