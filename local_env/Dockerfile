FROM takatost/nginx-php:latest

MAINTAINER JohnWang <wangjiajun@vchangyi.com>

VOLUME /data/www

CMD cd /data/www && \
    /usr/local/php/bin/php /data/www/composer.phar install --no-dev --prefer-dist && \
    /usr/local/php/bin/php /data/www/composer.phar update --no-dev --prefer-dist && \
    chown -R www:www /data/www