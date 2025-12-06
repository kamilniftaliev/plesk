-- Fix MySQL user authentication
DROP USER IF EXISTS 'u676821063_new2'@'localhost';
CREATE USER 'u676821063_new2'@'localhost' IDENTIFIED BY '!/F:6h[E9';
GRANT ALL PRIVILEGES ON `u676821063_new2`.* TO 'u676821063_new2'@'localhost';
FLUSH PRIVILEGES;

-- Verify user exists
SELECT User, Host FROM mysql.user WHERE User = 'u676821063_new2';
