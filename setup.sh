#!/bin/bash

echo "=== MySQL/MariaDB Database Setup for Plesk Project ==="
echo ""

# Start MariaDB service
echo "[1/5] Starting MariaDB service..."
sudo systemctl start mariadb
if [ $? -eq 0 ]; then
    echo "✓ MariaDB service started successfully"
else
    echo "✗ Failed to start MariaDB service"
    exit 1
fi

# Enable MariaDB to start on boot
echo "[2/5] Enabling MariaDB to start on boot..."
sudo systemctl enable mariadb

# Create database and user
echo "[3/5] Creating database and user..."
sudo mysql -e "CREATE DATABASE IF NOT EXISTS \`u676821063_new2\`;"
sudo mysql -e "CREATE USER IF NOT EXISTS 'u676821063_new2'@'localhost' IDENTIFIED BY '!/F:6h[E9';"
sudo mysql -e "GRANT ALL PRIVILEGES ON \`u676821063_new2\`.* TO 'u676821063_new2'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"
echo "✓ Database and user created"

# Import db.sql
echo "[4/5] Importing db.sql..."
sudo mysql u676821063_new2 < /home/user/Desktop/plesk/db.sql
if [ $? -eq 0 ]; then
    echo "✓ Database imported successfully"
else
    echo "✗ Failed to import database"
    exit 1
fi

# Verify database
echo "[5/5] Verifying database setup..."
sudo mysql -e "USE u676821063_new2; SHOW TABLES;"

echo ""
echo "=== Database Setup Complete ==="
echo ""
echo "Database Details:"
echo "  Host: localhost"
echo "  Database: u676821063_new2"
echo "  Username: u676821063_new2"
echo "  Password: !/F:6h[E9"
echo ""
