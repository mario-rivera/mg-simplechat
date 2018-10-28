FROM php:7.1-fpm

RUN apt-get update && apt-get install -y \
git \
zlib1g-dev

# install php extensions
RUN docker-php-ext-install \
zip

# install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
&& php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
&& php -r "unlink('composer-setup.php');"

# install xdebug
RUN pecl install xdebug \
&& docker-php-ext-enable xdebug

# Xdebug Config
COPY ./xdebug/xdebug.sample.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.sample.ini
# PHP ini file
COPY ./conf/php.ini-development /usr/local/etc/php/php.ini

RUN rm -rf /var/lib/apt/lists/*