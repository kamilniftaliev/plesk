-- Create database
CREATE DATABASE IF NOT EXISTS `u676821063_new2`;

-- Create user and grant privileges
CREATE USER IF NOT EXISTS 'u676821063_new2'@'localhost' IDENTIFIED BY '!/F:6h[E9';
GRANT ALL PRIVILEGES ON `u676821063_new2`.* TO 'u676821063_new2'@'localhost';
FLUSH PRIVILEGES;

-- Switch to the database
USE `u676821063_new2`;
