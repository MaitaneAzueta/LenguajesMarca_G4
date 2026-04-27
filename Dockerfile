FROM php:8.2-apache

# Instalamos la extensión de MySQL para PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copiamos tus archivos al servidor
COPY . /var/www/html/

# Exponemos el puerto 80 (el estándar de Apache)
EXPOSE 80