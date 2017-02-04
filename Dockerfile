FROM takatost/nginx-php:latest

MAINTAINER JohnWang <wangjiajun@vchangyi.com>

COPY src/ /data/www/
COPY configs/conf.d/queue.conf /etc/supervisor/conf.d/

RUN cd /data/www && \
    /usr/local/php/bin/php /data/www/composer.phar install --no-dev --prefer-dist && \
    /usr/local/php/bin/php /data/www/composer.phar update --no-dev --prefer-dist && \
    chown -R www:www /data/www

RUN apt-get install -y vim

#这样写法错误
#RUN /usr/local/php/bin/php artisan migrate

RUN apidoc -i /data/www/app/ -o /data/www/public/docs/


