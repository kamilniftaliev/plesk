<?php
/**
 * Router script for PHP built-in web server
 * This ensures static files are served correctly
 */

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $path;

// If the file exists and is not a directory, serve it as-is
if (file_exists($file) && !is_dir($file)) {
    // Set correct MIME type for static files
    $extension = pathinfo($file, PATHINFO_EXTENSION);

    $mimeTypes = [
        'js' => 'application/javascript',
        'css' => 'text/css',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
        'eot' => 'application/vnd.ms-fontobject',
        'html' => 'text/html',
        'htm' => 'text/html',
    ];

    if (isset($mimeTypes[$extension])) {
        header('Content-Type: ' . $mimeTypes[$extension]);
    }

    // For non-PHP files, just return the file
    if ($extension !== 'php') {
        return false; // Let PHP built-in server handle it
    }
}

// For PHP files or routes that don't exist, let PHP handle it normally
return false;
