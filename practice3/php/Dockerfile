FROM php:8.1-apache

RUN apt-get update && apt-get install -y libzip-dev unzip libaprutil1-dbd-mysql \
  && docker-php-ext-install mysqli \
  && a2enmod rewrite dbd authn_dbd \
  && rm -rf /var/lib/apt/lists/*

RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf \
  && echo "ServerName localhost" >> /etc/apache2/apache2.conf

WORKDIR /var/www/app_root

RUN sed -i 's|/var/www/html|/var/www/app_root|g' /etc/apache2/sites-available/000-default.conf

COPY apache-dbd.conf /etc/apache2/conf-available/apache-dbd.conf
RUN a2enconf apache-dbd
RUN a2enmod auth_basic

