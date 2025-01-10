# Usa una imagen base de PHP con Apache
FROM php:8.2-apache

# Instala las extensiones necesarias
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql

# Instala Composer
COPY composer.json composer.lock /var/www/html/
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --working-dir=/var/www/html

# Copia los archivos de tu proyecto
COPY . /var/www/html

# Configura permisos y el directorio de trabajo
WORKDIR /var/www/html
