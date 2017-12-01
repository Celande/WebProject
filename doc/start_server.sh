#!/bin/bash

sudo service apache2 restart # start | stop | status
sudo service mysql restart
cd /home/hyenaquenn/slim_framework; php -S localhost:8080 -t public public/index.php

# root
# Gardevoir

# https://doc.ubuntu-fr.org/mysql
# https://doc.ubuntu-fr.org/phpmyadmin

#https://www.slimframework.com/docs/tutorial/first-app.html

# boot mysql
mysql -u root

# load file to mysql
mysql -u root -p web_project < file.sql
