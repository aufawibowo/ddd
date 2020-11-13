FROM mileschou/phalcon:7.4-apache

RUN apt-get update

# # Install Phalcon
# RUN pecl install psr \
#     && docker-php-ext-enable psr

# RUN pecl install phalcon  \
#     && docker-php-ext-enable phalcon

# RUN pecl install xdebug \
#     && docker-php-ext-enable xdebug

RUN docker-php-ext-install pdo pdo_mysql

# Other dependencies
RUN apt update \
    && apt install -y htop nano git zip \
    && a2enmod rewrite headers

RUN service apache2 restart