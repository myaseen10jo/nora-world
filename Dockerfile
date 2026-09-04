FROM php:8.4-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev \
    libzip-dev libicu-dev libpq-dev zip unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl opcache \
    && docker-php-ext-configure intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock* ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist || true

COPY . .

RUN composer install --no-dev --optimize-autoloader \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && mkdir -p storage/app/public/products storage/app/public/categories storage/app/public/product-media \
    && chmod -R 775 storage bootstrap/cache \
    && chmod +x docker-entrypoint.sh \
    && rm -rf storage/framework/cache/*.php storage/framework/views/*.php bootstrap/cache/*.php

EXPOSE 8000

# Force HTTPS for reverse proxy environments like Railway
ENV FORCE_HTTPS=true

ENTRYPOINT ["sh", "docker-entrypoint.sh"]
