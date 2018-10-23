FROM kwindsor/php7-apache-ubuntu

RUN apt-get update \
    && docker-php-ext-install mysqli pdo pdo_mysql zip json mbstring \
    && docker-php-ext-enable mysqli pdo pdo_mysql zip json mbstring \
    && apt-get -y install curl git zip unzip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && echo "autooferty:x:1000:1000:autooferty,,,:/home/autooferty:/bin/bash" >> /etc/passwd
RUN mkdir /home/autooferty \
    && chown 1000:1000 /home/autooferty

ADD 000-default.conf /etc/apache2/sites-available
RUN rm /etc/apache2/sites-enabled/000-default.conf \
    && a2ensite 000-default.conf

# Expose apache.
EXPOSE 80

# By default start up apache in the foreground, override with /bin/bash for interative.
CMD /usr/sbin/apache2ctl -D FOREGROUND