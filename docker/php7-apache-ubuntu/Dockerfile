FROM kwindsor/php7-apache-ubuntu

RUN apt-get update \
    && docker-php-ext-install mysqli pdo pdo_mysql zip json mbstring \
    && docker-php-ext-enable mysqli pdo pdo_mysql zip json mbstring \
    && apt-get -y install curl git zip unzip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && echo "autooferty:x:1000:1000:autooferty,,,:/home/autooferty:/bin/bash" >> /etc/passwd
RUN mkdir /home/autooferty \
    && chown 1000:1000 /home/autooferty

CMD [ "tail", "-f", "/dev/null" ]
