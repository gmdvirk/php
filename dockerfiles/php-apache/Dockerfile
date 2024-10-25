FROM php:7.2-apache

# Install required packages and PHP extensions
RUN apt-get update && \
    apt-get install -y nano zip unzip git libicu-dev libzip-dev default-mysql-client && \
    docker-php-ext-configure intl && \
    docker-php-ext-configure zip --with-libzip && \
    docker-php-ext-install intl mysqli zip && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Install APCu
RUN pecl install apcu-5.1.17 && \
    docker-php-ext-enable apcu

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# Set the working directory
WORKDIR /var/www/html

# Set permissions so Apache (www-data) can access files
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Copy the custom Apache configuration
COPY ./dockerfiles/php-apache/codeigniter.conf /etc/apache2/sites-available/

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Enable the custom Apache site and reload the service
RUN a2ensite codeigniter.conf && \
    service apache2 reload || true

# Disable the default Apache site
RUN cd /etc/apache2/sites-available && \
    a2dissite 000-default.conf && \
    service apache2 reload || true

# Expose port 80 for the web server
EXPOSE 80
