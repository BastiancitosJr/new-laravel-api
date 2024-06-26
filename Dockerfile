FROM php:8.2-fpm AS laravelbuilder

# Install necessary dependencies and extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libaio1 \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Ensure the storage and cache directories exist and set permissions
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/storage/framework/cache/data \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

FROM laravelbuilder AS oraclebuilder
# Installing OCI8 extension in PHP using Oracle Instant Client
RUN mkdir -p /opt/oracle \
    && cd /opt/oracle \
    && curl -o instantclient-basic-linux.x64-23.4.0.24.05.zip https://download.oracle.com/otn_software/linux/instantclient/2340000/instantclient-basic-linux.x64-23.4.0.24.05.zip \
    && unzip instantclient-basic-linux.x64-23.4.0.24.05.zip \
    && mv instantclient_23_4 /opt/oracle/instantclient \
    && rm instantclient-basic-linux.x64-23.4.0.24.05.zip \
    && curl -o instantclient-sdk-linux.x64-23.4.0.24.05.zip https://download.oracle.com/otn_software/linux/instantclient/2340000/instantclient-sdk-linux.x64-23.4.0.24.05.zip \
    && unzip -o instantclient-sdk-linux.x64-23.4.0.24.05.zip \
    && mv instantclient_23_4/sdk/include /opt/oracle/instantclient/sdk \
    && rm instantclient-sdk-linux.x64-23.4.0.24.05.zip \
    && mkdir -p /opt/oracle/instantclient/sdk/include \
    && mv /opt/oracle/instantclient/sdk/*.h /opt/oracle/instantclient/sdk/include/

# Setting environment variables required for OCI8
ENV LD_LIBRARY_PATH /opt/oracle/instantclient
ENV ORACLE_HOME /opt/oracle/instantclient

# Installing the OCI8 extension in PHP
RUN docker-php-ext-configure oci8 --with-oci8=instantclient,/opt/oracle/instantclient,sdk \
    && docker-php-ext-install oci8

FROM oraclebuilder AS composerinstall

# Install yajra/laravel-oci8 using Composer inside the container
RUN composer require yajra/laravel-oci8

# Install darkaonline/l5-swagger using Composer inside the container
RUN composer require darkaonline/l5-swagger

# Install PHP dependencies using Composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Dump the Composer autoload
RUN composer dump-autoload

# Generate the Swagger documentation
RUN php artisan l5-swagger:generate

# Run the Laravel development server when the container is started
CMD php artisan serve --host=0.0.0.0 --port=8000

