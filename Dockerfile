FROM php:8.2-apache

# Install dependencies sistem (ditambah libpq-dev untuk PostgreSQL)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install ekstensi PHP (termasuk pdo_pgsql & pgsql untuk database PostgreSQL)
RUN docker-php-ext-install pdo_mysql pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Salin seluruh file project ke dalam container
COPY . /var/www/html

# Install dependencies PHP menggunakan composer
RUN composer install --no-dev --optimize-autoloader

# Atur izin folder storage dan bootstrap cache agar bisa ditulisi
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Ubah DocumentRoot Apache ke folder public Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf -e 's/AllowOverride None/AllowOverride All/g'

# Aktifkan mod_rewrite Apache
RUN a2enmod rewrite

# Sesuaikan port default Apache ke port 10000 (standar Render untuk Docker)
RUN sed -i 's/80/10000/g' /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 10000