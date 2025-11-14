FROM php:8.2-apache

# Apply OS security updates to ensure known vulnerabilities are patched
RUN set -eux; \
	apt-get update; \
	apt-get -y upgrade; \
	apt-get clean; \
	rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY . /var/www/html/

RUN a2enmod rewrite

# Supprimer l'avertissement "Could not reliably determine the server's fully qualified domain name"
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

EXPOSE 80
