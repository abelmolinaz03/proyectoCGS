FROM php:8.1-apache
# Instala la extensión PDO para MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Activa módulos de Apache
RUN a2enmod headers rewrite

# Reinicia Apache
RUN /etc/init.d/apache2 restart
