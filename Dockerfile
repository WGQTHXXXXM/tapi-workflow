FROM dockerhub.singulato.com/singulato/nginx-php:latest

WORKDIR /data/www

COPY . .

RUN cp ./etc/nginx/app.conf /etc/nginx/conf.d/app.conf && \
    crontab -u nginx /data/www/etc/crontab.conf && \
    composer install && \
    chmod -R 777 ./storage && \
    chmod -R 777 ./bootstrap/cache

#RUN  composer install -vvv

#RUN php artisan route:cache && \
#    php artisan api:cache && \
#    php artisan optimize --force && \
#    composer dumpautoload

COPY scripts /scripts/pre-init.d