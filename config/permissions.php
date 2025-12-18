<?php

/**
 * User Type Permissions Configuration
 *
 * This file defines what pages each user type can access.
 * Pages are identified by their slug (filename without .php extension)
 *
 * User Types:
 * - admin: Full access to all pages
 * - reseller: Access to Transactions, Refill Credit + all user pages
 * - user: Access to IMEI Checker, Job History, and Dashboard only
 */

// Page slug definitions with friendly names
define('PAGES', [
    // User accessible pages
    'index' => 'Dashboard',
    'imei_checker' => 'IMEI Checker',
    'individual_job' => 'Job History',

    // Reseller accessible pages (in addition to user pages)
    'penjualan' => 'Transactions',
    'transfer_credit' => 'Refill Credit',
    'paid_refil' => 'Paid Credit',
    'history_refil' => 'Refill History',

    // Admin only pages
    'customers' => 'Search Users',
    'reseller' => 'Resellers',
    'job' => 'Check History',
    'add_reseller' => 'Add Reseller',
    'edit_customer' => 'Edit Customer',
    'edit_reseller' => 'Edit Reseller',
    'edit_device' => 'Edit Device',
    'edit_userinfo' => 'Edit User Info',
    'edit_serverstatus' => 'Edit Server Status',
    'edit_price' => 'Edit Price',
    'edit_penjualan' => 'Edit Penjualan',
    'setpatch' => 'Set Patch',
    'add_server' => 'Add Server',
    'serverstatus' => 'Server Status',
    'serverspatch' => 'Servers Patch',
    'servers' => 'Servers',
    'price' => 'Price',
    'delete_customer' => 'Delete Customer',
    'delete_history_credit' => 'Delete History Credit',
    'delete_paid_credit' => 'Delete Paid Credit',
    'delete_server' => 'Delete Server',
    'delete_user' => 'Delete User',

    // Common pages (accessible to all authenticated users)
    'edit_profile' => 'Edit Profile',
    'edit_main' => 'Change Password',
    'logout' => 'Logout',
]);

// Permission matrix: defines which pages each user type can access
define('PERMISSIONS', [
    'user' => [
        'index',
        'imei_checker',
        'individual_job',
        'edit_profile',
        'edit_main',
        'logout',
    ],

    'reseller' => [
        // Reseller-specific pages
        'penjualan',
        'transfer_credit',
        'paid_refil',
        'history_refil',

        // All user pages
        'index',
        'imei_checker',
        'individual_job',
        'edit_profile',
        'edit_main',
        'logout',
    ],

    'admin' => [
        // Admin has access to ALL pages
        '*' // Wildcard means all pages
    ],
]);

/**
 * Get current user's type from database
 * Fetches the user type from the 'status' column in the 'user' table
 *
 * @return string User type (admin, reseller, or user)
 */
function getCurrentUserType()
{
    // Check if user is logged in
    $user_id = null;
    if (isset($_SESSION['admin_id'])) {
        $user_id = $_SESSION['admin_id'];
    } elseif (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    }

    if (!$user_id) {
        return 'user'; // Default to user if not logged in
    }

    // Fetch user type from database
    try {
        $db = getDbInstance();
        $db->where('id', $user_id);
        $user = $db->getOne('user', 'status');

        if ($user && isset($user['status'])) {
            return $user['status'];
        }
    } catch (Exception $e) {
        // Log error if needed
        error_log("Error fetching user type: " . $e->getMessage());
    }

    return 'user'; // Default to user if error or not found
}

/**
 * Check if a user type has permission to access a specific page
 *
 * @param string $user_type The type of user (admin, reseller, user)
 * @param string $page_slug The page slug to check permission for
 * @return bool True if user has permission, false otherwise
 */
function hasPermission($user_type, $page_slug)
{
    // Admin has access to everything
    if ($user_type === 'admin') {
        return true;
    }

    // Check if user type exists in permissions
    if (!isset(PERMISSIONS[$user_type])) {
        return false;
    }

    // Check if page is in the user's allowed pages
    return in_array($page_slug, PERMISSIONS[$user_type]);
}

/**
 * Get all pages that a user type can access
 *
 * @param string $user_type The type of user (admin, reseller, user)
 * @return array Array of page slugs the user can access
 */
function getAllowedPages($user_type)
{
    // Admin has access to all pages
    if ($user_type === 'admin') {
        return array_keys(PAGES);
    }

    // Return specific pages for the user type
    if (isset(PERMISSIONS[$user_type])) {
        return PERMISSIONS[$user_type];
    }

    return [];
}

/**
 * Check if current user can access the current page
 * Shows 403 error page if no permission
 *
 * @param string $current_page_slug The current page slug
 */
function requirePermission($current_page_slug)
{
    // Check if user is logged in
    if (!isset($_SESSION['user_logged_in']) || !$_SESSION['user_logged_in']) {
        $url_prefix = defined('URL_PREFIX') ? URL_PREFIX : '';
        header('Location: ' . $url_prefix . '/dashboard/login.php');
        exit();
    }

    // Get user type from database
    $user_type = getCurrentUserType();

    // Check permission
    if (!hasPermission($user_type, $current_page_slug)) {
        // Store the page name for the error page
        if (isset(PAGES[$current_page_slug])) {
            $_SESSION['permission_page_name'] = PAGES[$current_page_slug];
        }

        // Show 403 error page
        http_response_code(403);
        include BASE_PATH . '/includes/403.php';
        exit();
    }
}

/**
 * Get page slug from filename
 *
 * @param string $filename The filename (e.g., 'index.php' or '/path/to/index.php')
 * @return string The page slug (e.g., 'index')
 */
function getPageSlug($filename)
{
    $basename = basename($filename);
    return str_replace('.php', '', $basename);
}

/**
 * Check if a menu item should be visible for the current user
 *
 * @param string $page_slug The page slug
 * @param string|null $user_type Optional user type, fetches from database if not provided
 * @return bool True if menu item should be visible
 */
function isMenuVisible($page_slug, $user_type = null)
{
    if ($user_type === null) {
        $user_type = getCurrentUserType();
    }

    return hasPermission($user_type, $page_slug);
}
