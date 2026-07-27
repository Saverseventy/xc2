FROM php:8.2-apache

# Enable required modules
RUN a2enmod rewrite headers expires

# Copy ALL files properly
COPY ./ /var/www/html/

# Fix Apache config: allow .htaccess + set DirectoryIndex properly
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/sites-available/000-default.conf
RUN echo "DirectoryIndex index.html index.php player_api.php get.php" >> /etc/apache2/mods-enabled/dir.conf

# Fix permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]
