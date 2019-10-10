#!/bin/sh
echo "* 0/15 * ? * * * php -f $PWD/main.php" >> mycron
crontab mycron
rm mycron