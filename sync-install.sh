mysql --user=root -e "drop database metator"
mysql --user=root -e "create database metator"
./metator phinx migrate
mysqldump --user=root metator>install.sql