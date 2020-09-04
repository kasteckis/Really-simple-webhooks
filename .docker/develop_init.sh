#!/bin/sh
sleep 10
cd /var/www/html
composer install
npm install
npm run scss
npm run build
php bin/console d:s:u --force
#php bin/console doctrine:fixtures:load --append
echo "Project has been started"
echo "Access project website - http://192.168.4.2/"
echo "MYSQL Server - 192.168.4.3:3306"
echo "Access phpmyadmin via - http://192.168.4.4/"
echo "Access email server via - http://192.168.4.5:8025/"
echo "Access server SSH by writing - docker exec -it trigger_web bash"