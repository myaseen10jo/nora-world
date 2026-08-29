FROM php:8.4-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev \
    libzip-dev libicu-dev libpq-dev zip unzip \
    && docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd zip intl opcache \
    && docker-php-ext-configure intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock* ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist || true

COPY . .

RUN composer install --no-dev --optimize-autoloader \
    && touch .env \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8000

CMD ["sh", "-c", "touch .env && php artisan key:generate --no-interaction 2>/dev/null; php artisan migrate --force 2>/dev/null; exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"]
