#!/bin/bash

# Mengganti port default Apache dengan port dinamis dari Render
sed -i "s/80/${PORT:-10000}/g" /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf

# Bersihkan semua cache Laravel agar CSS/Vite terbaca sempurna
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache

# Jalankan migrasi database
php artisan migrate --force

# Jalankan Apache
apache2-foreground