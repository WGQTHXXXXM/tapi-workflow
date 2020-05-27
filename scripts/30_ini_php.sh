/bin/sh
/usr/bin/php /data/www/artisan service:discovery && echo "注册ok" >>  /tmp/startup.log
chmod -R 777 /data/www/storage