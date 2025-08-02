# STAGE 1: Membangun Dependensi (Analoginya Seperti Bengkel)
FROM composer:latest AS builder
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-plugins --no-scripts
COPY . .

# STAGE 2: Build final image yang bersih (insyaAllah ringan)
FROM php:8.2-fpm-alpine
WORKDIR /var/www/html
# Instal ekstensi PHP yang dibutuhkan saat runtime
RUN apk add --no-cache \
    mysql-dev \
    libpng-dev \
    libzip-dev \
    jpeg-dev \
    freetype-dev \
    oniguruma-dev
# Konfigurasi dan install ekstensi gd yang lengkap
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && docker-php-ext-install -j$(nproc) gd
# Install ekstensi PHP umum
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath zip
# Salin composer executable agar bisa digunakan di dalam container
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# Salin hanya hasil akhir dari stagre builder
COPY --from=builder /app .
RUN chown -R www-data:www-data /var/www/html
EXPOSE 9000
CMD ["php-fpm"]