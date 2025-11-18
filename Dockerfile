# Image officielle PHP + Apache
FROM php:8.2-apache

# Mise à jour des paquets (sécurité)
RUN apt-get update && apt-get upgrade -y && apt-get clean && rm -rf /var/lib/apt/lists/*

# Variable pour casser le cache Render
ARG CACHEBUST=1

# Copie de ton portfolio dans Apache
COPY . /var/www/html/

# Activation du module rewrite (au cas où)
RUN a2enmod rewrite

# Permissions correctes
RUN chown -R www-data:www-data /var/www/html

# Port exposé pour Render
EXPOSE 80

# Lancement d'Apache
CMD ["apache2-foreground"]
