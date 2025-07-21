FROM php:8.1-apache

# Enable mysqli & PDO extensions
RUN docker-php-ext-install mysqli pdo_mysql

# (Optional) enable Apache rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html

# Copy app into container
COPY . /var/www/html

# Fix permissions
RUN chown -R www-data:www-data /var/www/html
