# Gunakan image PHP yang terbaru (sesuai dengan kebutuhan Laravel 11)
FROM php:8.2-fpm

# Install sistem dependensi yang diperlukan untuk Laravel dan ekstensi PHP
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    git \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory di dalam container
WORKDIR /var/www

# Salin file proyek Laravel Anda ke dalam container
COPY . .

# Install dependencies Laravel dengan Composer
RUN composer install --no-dev --optimize-autoloader

# Set permission folder storage dan bootstrap/cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expose port yang digunakan oleh PHP-FPM
EXPOSE 9000

# Jalankan PHP-FPM server
CMD ["php-fpm"]
