#!/bin/bash

echo "=== Starting PHP Development Server ==="
echo ""

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "✗ PHP is not installed. Please install PHP first."
    exit 1
fi

# Display PHP version
echo "PHP Version: $(php -v | head -n 1)"
echo ""

# Start PHP built-in server
PORT=8000
echo "Starting server on http://localhost:$PORT"
echo "Press Ctrl+C to stop the server"
echo ""

cd /home/user/Desktop/plesk
php -S localhost:$PORT

# Alternative: if you want a different port, run:
# php -S localhost:8080
