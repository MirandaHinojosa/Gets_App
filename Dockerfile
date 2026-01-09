FROM php:8.2-apache

# 1. Instala las extensiones de MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# 2. Habilita mod_rewrite para Apache
RUN a2enmod rewrite

# 3. Copia todos tus archivos PHP al servidor
COPY . /var/www/html/

# 4. Configura permisos
RUN chown -R www-data:www-data /var/www/html

# 5. Expone el puerto 80
EXPOSE 80

# 6. Inicia Apache
CMD ["apache2-foreground"]
