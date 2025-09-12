FROM php:8.2-apache

# Install Composer dependencies
RUN apt update && \
    apt install -y zlib1g-dev libzip-dev unzip && \
    docker-php-ext-install zip

# Install and update Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN composer self-update

WORKDIR /var/www/html
COPY . .

# Install project dependencies
CMD bash -c "composer install && apache2-foreground"

EXPOSE 80
