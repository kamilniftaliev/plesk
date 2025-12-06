# Admin Type Warning Fix

## Problem

Error when accessing protected pages:
```
Warning: Undefined array key "admin_type" in /home/user/Desktop/plesk/reseller/add_server.php on line 7
401 Unauthorized
```

## Root Cause

Multiple files checked `$_SESSION['admin_type']` without first verifying the key exists using `isset()`:

```php
// WRONG - causes warning if not logged in
if ($_SESSION['admin_type'] !== 'admin') {
    exit('401 Unauthorized');
}
```

## Solution

### 1. Fixed auth_validate.php redirect
**File:** `reseller/includes/auth_validate.php`

**Before:**
```php
if (!isset($_SESSION['user_logged_in'])) {
    header('Location:dashboard/login.php'); // Wrong path!
}
```

**After:**
```php
if (!isset($_SESSION['user_logged_in'])) {
    header('Location:login.php'); // Correct path for reseller
}
```

### 2. Fixed header files

**Files:**
- `reseller/includes/admin_header.php`
- `reseller/includes/reseller_header.php`
- `reseller/includes/user_header.php`

**Before:**
```php
<php  // Syntax error!
if ($_SESSION['admin_type'] !== 'admin') {
```

**After:**
```php
<?php  // Fixed syntax
if (!isset($_SESSION['admin_type']) || $_SESSION['admin_type'] !== 'admin') {
```

### 3. Fixed all PHP files

Updated all files to check with `isset()` before accessing `admin_type`:

**Pattern for inequality checks:**
```php
// Before
if ($_SESSION['admin_type'] !== 'admin')

// After
if (!isset($_SESSION['admin_type']) || $_SESSION['admin_type'] !== 'admin')
```

**Pattern for equality checks:**
```php
// Before
if ($_SESSION['admin_type'] == 'user')

// After
if (isset($_SESSION['admin_type']) && $_SESSION['admin_type'] == 'user')
```

### Files Updated

All reseller PHP files with admin_type checks:
- add_customer.php
- add_reseller.php
- add_server.php
- customers.php
- delete_customer.php
- delete_history_credit.php
- delete_paid_credit.php
- delete_server.php
- delete_user.php
- edit_main.php
- edit_penjualan.php
- edit_price.php
- edit_reseller.php
- edit_servers.php
- edit_serverstatus.php
- edit_userinfo.php
- And all header files

## Result

✅ No more "Undefined array key" warnings
✅ Proper 401 response when not logged in
✅ Correct redirect to reseller login page
✅ Fixed PHP syntax errors in header files

## Testing

Try accessing a protected page without logging in:
- http://localhost:8080/reseller/add_server.php
- Expected: Clean "401 Unauthorized" message (no warnings)

After logging in with correct credentials:
- Pages should work normally
- Session variable will be set properly
