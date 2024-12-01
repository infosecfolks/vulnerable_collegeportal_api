# Use a base image
FROM debian:bookworm

# Install dependencies and Apache
RUN apt-get update && apt-get install -y \
    apache2 \
    gnupg \
    lsb-release \
    apt-transport-https \
    ca-certificates \
    wget

# Add sury.org repository for PHP 8.1
RUN wget -qO /usr/share/keyrings/sury-php.gpg https://packages.sury.org/php/apt.gpg && \
    echo "deb [signed-by=/usr/share/keyrings/sury-php.gpg] https://packages.sury.org/php/ bookworm main" > /etc/apt/sources.list.d/php.list && \
    apt-get update

# Install PHP 8.1 and related packages
RUN apt-get install -y \
    libapache2-mod-php8.1 \
    php8.1-cli \
    php8.1-opcache \
    php8.1-mysqli && \
    apt-get clean

# Enable mod_rewrite for Apache
RUN a2enmod rewrite

# Update apache2.conf or the 000-default.conf to allow .htaccess override
RUN echo '<Directory /var/www/html>\n\
        AllowOverride All\n\
    </Directory>' >> /etc/apache2/apache2.conf

# Or, alternatively, modify the virtual host file directly
# RUN echo '<Directory /var/www/html>\n\
#        AllowOverride All\n\
#    </Directory>' >> /etc/apache2/sites-available/000-default.conf

# Copy application files
COPY ./src /var/www/html/

# Set up .htaccess for URL rewriting
COPY ./src/routes/.htaccess /var/www/html/src/routes/.htaccess

# Set file permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2ctl", "-D", "FOREGROUND"]
