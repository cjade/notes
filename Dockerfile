ARG PHP_VERSION
FROM php:${PHP_VERSION}-fpm

ARG PHP_XDEBUG
ARG PHP_REDIS
ARG REPLACE_SOURCE_LIST

COPY ./sources.list /etc/apt/sources.list.tmp
RUN if [ "${REPLACE_SOURCE_LIST}" = "true" ]; then \
    mv /etc/apt/sources.list.tmp /etc/apt/sources.list; else \
    rm -rf /etc/apt/sources.list.tmp; fi
RUN apt update

# Install extensions from source
COPY ./extensions /tmp/extensions
RUN chmod +x /tmp/extensions/install.sh \
    && /tmp/extensions/install.sh \
    && rm -rf /tmp/extensions

# More extensions
# 1. soap requires libxml2-dev.
# 2. xml, xmlrpc, wddx require libxml2-dev and libxslt-dev.
# 3. Line `&& :\` do nothing just for better reading.
RUN apt install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install $mc gd \
    && :\
    && apt install -y libicu-dev \
    && docker-php-ext-install $mc intl \
    && :\
    && apt install -y libbz2-dev \
    && docker-php-ext-install $mc bz2 \
    && :\
    && docker-php-ext-install $mc zip \
    && docker-php-ext-install $mc pdo_mysql \
    && docker-php-ext-install $mc mysqli \
    && docker-php-ext-install $mc mbstring \
    && docker-php-ext-install $mc exif \
    && docker-php-ext-install $mc bcmath \
    && docker-php-ext-install $mc calendar \
    && docker-php-ext-install $mc sockets \
    && docker-php-ext-install $mc gettext \
    && docker-php-ext-install $mc sysvmsg \
    && docker-php-ext-install $mc sysvsem \
    && docker-php-ext-install $mc sysvshm \
    && :\
    && apt install -y libxml2-dev \
    && apt install -y libxslt-dev \
    && docker-php-ext-install $mc soap \
    && docker-php-ext-install $mc xsl \
    && docker-php-ext-install $mc xmlrpc \
    && :\
    && apt install -y curl \
    && apt install -y libcurl3 \
    && apt install -y libcurl4-openssl-dev \
    && docker-php-ext-install $mc curl \
    && :\
    && apt install -y libsnmp-dev \
    && apt install -y snmp \
    && docker-php-ext-install $mc snmp \
    && :\
    && apt install -y libtidy-dev \
    && docker-php-ext-install $mc tidy \
    && :\
    && apt install -y libgmp-dev \
    && ln -s /usr/include/x86_64-linux-gnu/gmp.h /usr/include/gmp.h \
    && docker-php-ext-install $mc gmp \
    && :\
    && apt install -y mysql-client \
    && :\
    && apt install -y libmagickwand-dev \
    && pecl install imagick-3.4.3 \
    && docker-php-ext-enable imagick \
    && :\
    && apt install -y libmemcached-dev zlib1g-dev \
    && pecl install memcached-3.1.3 \
    && docker-php-ext-enable memcached