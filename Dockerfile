
# ==========================================
# 1. Build frontend menggunakan Node.js 22
# ==========================================
FROM node:22-bookworm-slim AS frontend

WORKDIR /app

COPY package.json package-lock.json ./

RUN npm ci

COPY . .

RUN npm run build


# ==========================================
# 2. PHP 8.4 + Apache untuk Laravel 13
# ==========================================
FROM php:8.4-apache

WORKDIR /var/www/html

# Install library dan PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libxml2-dev \
    unzip \
    git \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        exif \
        gd \
        intl \
        mbstring \
        opcache \
        pcntl \
        pdo_pgsql \
        zip \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Konfigurasi Apache menggunakan port Render
RUN sed -ri 's!/var/www/html!/var/www/html/public!g' \
        /etc/apache2/sites-available/000-default.conf \
    && sed -ri 's/<VirtualHost \*:80>/<VirtualHost *:10000>/' \
        /etc/apache2/sites-available/000-default.conf \
    && sed -ri 's/Listen 80/Listen 10000/' \
        /etc/apache2/ports.conf \
    && printf '\n<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>\n' >> /etc/apache2/apache2.conf

# Copy source Laravel
COPY . .

# Copy hasil build Vite
COPY --from=frontend /app/public/build /var/www/html/public/build

# Install dependency Laravel production
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader

# Permission Laravel
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Environment
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr
ENV PORT=10000

EXPOSE 10000

CMD ["apache2-foreground"]
