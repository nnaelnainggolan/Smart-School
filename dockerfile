FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev curl \
    && docker-php-ext-install zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

EXPOSE 8080

CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8080