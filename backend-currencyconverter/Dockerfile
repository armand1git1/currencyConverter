# Use an official PHP image with Apache
FROM php:7.4-apache

# Install required PHP extensions and dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev libcurl4-openssl-dev curl unzip libonig-dev \
    && docker-php-ext-install curl json mbstring zip \
    && docker-php-ext-enable curl json mbstring zip

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set the ServerName to suppress the warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Allow access to all files in /var/www/html
RUN printf "<Directory /var/www/html>\n    Options Indexes FollowSymLinks\n    AllowOverride All\n    Require all granted\n</Directory>\n" >> /etc/apache2/apache2.conf

# Redirect 404 for favicon.ico
RUN echo "Redirect 404 /favicon.ico" >> /etc/apache2/apache2.conf

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# Set the working directory inside the container
WORKDIR /var/www/html

# Copy the application files to the container
COPY . /var/www/html

# Install PHP dependencies using Composer
RUN composer require php-curl-class/php-curl-class \
    monolog/monolog \
    vlucas/phpdotenv \
    symfony/cache:^5.4 \
    psr/simple-cache

# Set permissions for the application files
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose port 80 for the web server
EXPOSE 80

# Start Apache when the container starts
CMD ["apache2-foreground"]