# Simple Coach Hire — website on Render (Docker: PHP 8.3 + Apache + MySQL/PDO)
FROM php:8.3-apache

# MySQL driver (PDO) + modules the site needs
RUN docker-php-ext-install pdo_mysql \
 && a2enmod rewrite headers

# Let our .htaccess pretty-URL rules work
RUN sed -ri 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Sensible PHP defaults for a small production site
RUN { \
      echo 'display_errors=Off'; \
      echo 'log_errors=On'; \
      echo 'expose_php=Off'; \
      echo 'upload_max_filesize=16M'; \
      echo 'post_max_size=16M'; \
    } > /usr/local/etc/php/conf.d/zz-site.ini

# App code (the whole website/ folder is the web root)
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html

# Render supplies $PORT at runtime — make Apache listen on it (default 10000)
ENV PORT=10000
CMD sed -i "s/^Listen 80$/Listen ${PORT}/" /etc/apache2/ports.conf \
 && sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf \
 && exec apache2-foreground
