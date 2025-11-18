FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u 1000 -d /home/mechanic mechanic
RUN mkdir -p /home/mechanic/.composer && \
    chown -R mechanic:mechanic /home/mechanic

# Set permissions
RUN chown -R mechanic:www-data /var/www/html

# Switch to mechanic user
USER mechanic

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
