#!/bin/bash

echo "=== System Status Check ==="
echo ""

# Check MySQL service
echo "[1] MySQL/MariaDB Service Status:"
sudo systemctl is-active mariadb && echo "✅ MariaDB is running" || echo "❌ MariaDB is NOT running"
echo ""

# Check if database exists
echo "[2] Database Check:"
sudo mysql -e "SHOW DATABASES LIKE 'u676821063_new2';" 2>/dev/null
echo ""

# Check if user exists
echo "[3] User Check:"
sudo mysql -e "SELECT User, Host FROM mysql.user WHERE User = 'u676821063_new2';" 2>/dev/null
echo ""

# Check user privileges
echo "[4] User Privileges:"
sudo mysql -e "SHOW GRANTS FOR 'u676821063_new2'@'localhost';" 2>/dev/null
echo ""

# Check tables in database
echo "[5] Tables in Database:"
sudo mysql -e "USE u676821063_new2; SHOW TABLES;" 2>/dev/null
echo ""

# Try to connect as the user
echo "[6] Connection Test:"
mysql -u u676821063_new2 -p'!/F:6h[E9' -e "SELECT 'Connection successful!' AS Status;" u676821063_new2 2>&1
echo ""

echo "=== Check Complete ==="
