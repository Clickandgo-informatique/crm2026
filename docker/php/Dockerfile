FROM php:8.4-fpm

# Installer les dépendances système nécessaires à Symfony et aux extensions PHP
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    postgresql-client \
    libgd-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libwebp-dev \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        --with-webp \
    && docker-php-ext-install \
        intl \
        pdo \
        pdo_pgsql \
        zip \
        gd \
    && rm -rf /var/lib/apt/lists/*

# Installer Redis via PECL
RUN pecl install redis \
    && docker-php-ext-enable redis

# Installer Xdebug (dev uniquement)
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier l'entrypoint
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

CMD ["php-fpm", "-F"]
