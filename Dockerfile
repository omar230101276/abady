# --- Stage 1: Build Frontend Assets with Node ---
FROM node:18-alpine AS node-builder
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# --- Stage 2: Install PHP dependencies ---
FROM composer:2 AS composer-builder
WORKDIR /app
COPY composer*.json ./
RUN composer install --no-dev --no-scripts --no-interaction --prefer-dist --optimize-autoloader
COPY . .
RUN composer dump-autoload --no-dev --optimize-autoloader

# --- Stage 3: Production Runner Image ---
FROM richarvey/nginx-php-fpm:3.1.6
WORKDIR /var/www/html

# Copy application files
COPY . .

# Copy vendor packages and built asset files from previous stages
COPY --from=composer-builder /app/vendor ./vendor
COPY --from=node-builder /app/public/build ./public/build

# Configure richarvey/nginx-php-fpm environment parameters
ENV WEBROOT /var/www/html/public
ENV SKIP_COMPOSER 1
ENV PHP_ERRORS_STDERR 1
ENV RUN_MIGRATIONS 0

# Adjust folder permissions for storage and bootstrap cache
RUN chown -R nginx:nginx /var/www/html/storage /var/www/html/bootstrap/cache
