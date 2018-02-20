FROM php:7.0.27-fpm

RUN apt-get update
RUN apt-get install -y libzip-dev mysql-client-5.5
RUN curl -LO https://github.com/Yelp/dumb-init/releases/download/v1.2.0/dumb-init_1.2.0_amd64.deb
RUN dpkg -i dumb-init_*.deb
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN pecl channel-update pecl.php.net
RUN pecl install zip
RUN docker-php-ext-install pdo_mysql zip
RUN apt-get clean
RUN rm -rf /dumb-init_1.2.0_amd64.deb /tmp/* /var/lib/apt/lists/* /var/tmp/*

WORKDIR /var/www/html

ENTRYPOINT ["/usr/bin/dumb-init", "--"]
