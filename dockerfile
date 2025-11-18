# Image officielle PHP + Apache
FROM php:8.2-apache

# Met à jour les paquets système pour corriger les vulnérabilités connues
RUN apt-get update && apt-get upgrade -y && apt-get clean && rm -rf /var/lib/apt/lists/*

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
