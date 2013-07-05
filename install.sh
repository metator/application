`mysql --user=root -e "drop database IF EXISTS metator"`;
`mysql --user=root -e "create database metator"`;
`mysql --user=root metator < install.sql`;