FROM php:8.1-apache

# Установка необходимых расширений
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
  && docker-php-ext-install mysqli pdo pdo_mysql \
  && a2enmod rewrite \
  && rm -rf /var/lib/apt/lists/*

# Установка Redis extension через PECL
RUN pecl install redis \
  && docker-php-ext-enable redis

# Настройка PHP для сессий в Redis
RUN { \
    echo 'session.save_handler = redis'; \
    echo 'session.save_path = "tcp://redis:6379"'; \
    echo 'session.gc_maxlifetime = 3600'; \
    echo 'session.cookie_lifetime = 3600'; \
    echo 'upload_max_filesize = 50M'; \
    echo 'post_max_size = 50M'; \
    echo 'max_file_uploads = 10'; \
} > /usr/local/etc/php/conf.d/custom.ini

# Включение AllowOverride для .htaccess
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

WORKDIR /var/www/html

