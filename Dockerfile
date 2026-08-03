FROM php:8.2-apache

# Enable required Apache modules
RUN a2enmod rewrite headers expires

# Enable .htaccess support — ensure ALL directories can use it
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf && \
    sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/sites-available/000-default.conf

# Set proper directory index — player_api.php FIRST so players find it
RUN sed -i 's/DirectoryIndex.*/DirectoryIndex player_api.php index.php index.html/' /etc/apache2/mods-enabled/dir.conf

# Set recommended PHP settings for IPTV API
RUN echo "file_uploads = On\n" \
    "memory_limit = 256M\n" \
    "upload_max_filesize = 64M\n" \
    "post_max_size = 64M\n" \
    "max_execution_time = 300\n" \
    "date.timezone = America/Sao_Paulo\n" \
    > /usr/local/etc/php/conf.d/iptv-settings.ini

# Copy application files
COPY ./ /var/www/html/

# Fix permissions
RUN chown -R www-data:www-data /var/www/html && \
    find /var/www/html -type d -exec chmod 755 {} \; && \
    find /var/www/html -type f -exec chmod 644 {} \;

EXPOSE 80
CMD ["apache2-foreground"]
