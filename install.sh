mysql --user=root -e "drop database IF EXISTS metator"
mysql --user=root -e "create database metator"
php public/index.php phinx migrate