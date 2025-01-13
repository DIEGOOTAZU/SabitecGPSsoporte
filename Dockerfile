# Usa una imagen base de PHP con Apache
FROM php:8.2-apache

# Instala las extensiones y herramientas necesarias
RUN apt-get update && apt-get install -y \
    libpq-dev \                
    libjpeg-dev \              
    libpng-dev \               
    libfreetype6-dev \        
    libzip-dev \              
    unzip \                   
    libonig-dev \              
    libxslt1-dev \            
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        gd \                  
        pdo_pgsql \            
        zip \                  
        mbstring \             
        xsl                    

# Instala Composer
COPY composer.json composer.lock /var/www/html/
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --optimize-autoloader --working-dir=/var/www/html

# Copia todos los archivos de tu proyecto
COPY . /var/www/html

# Configura permisos y el directorio de trabajo
WORKDIR /var/www/html

# Asegúrate de que los permisos sean correctos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Exponer el puerto 80
EXPOSE 80

# Comando por defecto para iniciar Apache
CMD ["apache2-foreground"]