FROM php:8.2-apache

# Habilita mod_rewrite para Apache
RUN a2enmod rewrite

# Copia todos tus archivos PHP al servidor
COPY . /var/www/html/

# Expone el puerto 80
EXPOSE 80

# Inicia Apache
CMD ["apache2-foreground"]