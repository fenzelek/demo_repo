# Wybierz odpowiednią wersję PHP
FROM php:8.2-fpm

# Instalacja zależności
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    pkg-config \
    libxrender1 \
    libfontconfig1 \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Instalacja rozszerzeń PHP
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Instalacja narzędzia Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Ustawienie timezone
RUN ln -fs /usr/share/zoneinfo/Europe/Warsaw /etc/localtime && dpkg-reconfigure -f noninteractive tzdata

RUN apt-get update && apt-get install -y nodejs npm


# Install XDebug
RUN pecl install xdebug-3.2.1 && docker-php-ext-enable xdebug
# Copy configurations
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug-dev.ini

WORKDIR /var/www

# RUN echo "memory_limit=256M" > /usr/local/etc/php/conf.d/memory-limit.ini

RUN chown -R www-data:www-data /var/www
RUN chmod -R 775 /var/www

# Ekspozycja portów
EXPOSE 9000

# Komenda startowa
CMD ["php-fpm"]
