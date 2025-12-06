#!/bin/bash

echo "=========================================="
echo "  Complete Database Setup for Plesk"
echo "=========================================="
echo ""

# Start MariaDB if not running
echo "[1/5] Checking MariaDB service..."
if ! sudo systemctl is-active --quiet mariadb; then
    echo "  Starting MariaDB..."
    sudo systemctl start mariadb
    sudo systemctl enable mariadb
    echo "  ✅ MariaDB started"
else
    echo "  ✅ MariaDB already running"
fi
echo ""

# Create databases and users
echo "[2/5] Creating databases and users..."
sudo mysql < /home/user/Desktop/plesk/setup_all_databases.sql
if [ $? -eq 0 ]; then
    echo "  ✅ Databases and users created successfully"
else
    echo "  ❌ Failed to create databases and users"
    exit 1
fi
echo ""

# Import db.sql into main database
echo "[3/5] Importing db.sql into u676821063_new2..."
sudo mysql u676821063_new2 < /home/user/Desktop/plesk/db.sql
if [ $? -eq 0 ]; then
    echo "  ✅ Data imported into u676821063_new2"
else
    echo "  ❌ Failed to import into u676821063_new2"
    exit 1
fi
echo ""

# Import db.sql into second database
echo "[4/5] Importing db.sql into u344104150_bd..."
sudo mysql u344104150_bd < /home/user/Desktop/plesk/db.sql
if [ $? -eq 0 ]; then
    echo "  ✅ Data imported into u344104150_bd"
else
    echo "  ❌ Failed to import into u344104150_bd"
    exit 1
fi
echo ""

# Verify setup
echo "[5/5] Verifying setup..."
echo ""
echo "Database: u676821063_new2"
sudo mysql -e "USE u676821063_new2; SELECT COUNT(*) as 'Table Count' FROM information_schema.tables WHERE table_schema = 'u676821063_new2';"
echo ""
echo "Database: u344104150_bd"
sudo mysql -e "USE u344104150_bd; SELECT COUNT(*) as 'Table Count' FROM information_schema.tables WHERE table_schema = 'u344104150_bd';"
echo ""

echo "=========================================="
echo "  Setup Complete!"
echo "=========================================="
echo ""
echo "Database Configurations:"
echo ""
echo "1. Main Database:"
echo "   Host: localhost"
echo "   Database: u676821063_new2"
echo "   Username: u676821063_new2"
echo "   Password: !/F:6h[E9"
echo ""
echo "2. Dashboard Includes Database:"
echo "   Host: localhost"
echo "   Database: u344104150_bd"
echo "   Username: u344104150_bd"
echo "   Password: XiaomiBD2K24"
echo ""
echo "You can now access:"
echo "  - Main site: http://localhost:8080"
echo "  - Dashboard: http://localhost:8080/dashboard"
echo ""
