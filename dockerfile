# Image officielle PHP + Apache
FROM php:8.2-apache

# Copie de ton code dans Apache
COPY . /var/www/html/

# Activation de mod_rewrite si nécessaire (pour routes propres)
RUN a2enmod rewrite

# Permissions correctes
RUN chown -R www-data:www-data /var/www/html

# Expose le port utilisé par Render
EXPOSE 80

# Démarre Apache
CMD ["apache2-foreground"]
