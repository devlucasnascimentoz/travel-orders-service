FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

RUN echo "listen = 9000" > /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo "pm.max_children = 20" >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo "pm.start_servers = 2" >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo "pm.min_spare_servers = 1" >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo "pm.max_spare_servers = 3" >> /usr/local/etc/php-fpm.d/zz-docker.conf

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . .

COPY docker/patches.sh /tmp/patches.sh
RUN chmod +x /tmp/patches.sh
RUN composer install --no-dev --optimize-autoloader
RUN /tmp/patches.sh --no-dev --optimize-autoloader && \
    chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

CMD ["php-fpm", "-F"]
