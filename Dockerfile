# Menggunakan base image PHP 8.2 dengan Apache
FROM php:8.2-apache

# Mengubah document root Apache agar mengarah ke folder public Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Mengaktifkan mod_rewrite Apache (wajib untuk routing Laravel)
RUN a2enmod rewrite

# Menginstal dependensi sistem yang dibutuhkan
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libssl-dev \
    pkg-config \
    libcurl4-openssl-dev

# Menginstal ekstensi PHP bawaan yang dibutuhkan Laravel
RUN docker-php-ext-install zip pdo pdo_mysql

# Menginstal ekstensi MongoDB via PECL dan mengaktifkannya
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Menginstal Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Menentukan direktori kerja di dalam container
WORKDIR /var/www/html

# Menyalin seluruh file project lu ke dalam container
COPY . .

# Menjalankan instalasi dependency Laravel
RUN composer install --no-dev --optimize-autoloader

# Memberikan hak akses (permission) pada folder storage dan cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Membuka port 80 untuk lalu lintas web
EXPOSE 80