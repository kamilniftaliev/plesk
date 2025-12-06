-- Setup script for all databases required by the project

-- Database 1: u676821063_new2 (Main database)
DROP DATABASE IF EXISTS `u676821063_new2`;
CREATE DATABASE `u676821063_new2`;

DROP USER IF EXISTS 'u676821063_new2'@'localhost';
CREATE USER 'u676821063_new2'@'localhost' IDENTIFIED BY '!/F:6h[E9';
GRANT ALL PRIVILEGES ON `u676821063_new2`.* TO 'u676821063_new2'@'localhost';

-- Database 2: u344104150_bd (Dashboard includes database)
DROP DATABASE IF EXISTS `u344104150_bd`;
CREATE DATABASE `u344104150_bd`;

DROP USER IF EXISTS 'u344104150_bd'@'localhost';
CREATE USER 'u344104150_bd'@'localhost' IDENTIFIED BY 'XiaomiBD2K24';
GRANT ALL PRIVILEGES ON `u344104150_bd`.* TO 'u344104150_bd'@'localhost';

FLUSH PRIVILEGES;

-- Show created databases
SHOW DATABASES LIKE 'u%';

-- Show created users
SELECT User, Host FROM mysql.user WHERE User LIKE 'u%';
