# Session Name Warning Fix

## Problem

Error on every reseller page:
```
Warning: session_name(): Session name cannot be changed when a session is active
in /home/user/Desktop/plesk/reseller/config/config.php on line 8
```

## Root Cause

The `session_name()` function must be called **BEFORE** `session_start()`.

In the reseller folder:
- Many files called `session_start()` at the top
- Then included `config/config.php`
- Config file tried to call `session_name()` AFTER session was already started
- Result: Warning on every page

## Solution

### Step 1: Removed session_name from config.php
- **File:** `reseller/config/config.php`
- **Removed:** `session_name('RESELLER_SESSION');`
- **Reason:** Config is included after session starts

### Step 2: Added session_name before each session_start
- **Files:** All 39 PHP files in reseller folder that use sessions
- **Pattern:**
  ```php
  <?php
  session_name('RESELLER_SESSION');  // <-- Added this line
  session_start();
  ```

### Files Modified (39 files)

All files with `session_start()` now have `session_name('RESELLER_SESSION');` before it:

- add_customer.php
- add_device.php
- add_reseller.php
- add_server.php
- apilogin.php
- apilogout.php
- authenticate.php
- changepass.php
- customers.php
- delete_customer.php
- delete_history_credit.php
- delete_paid_credit.php
- delete_server.php
- delete_user.php
- edit_customer.php
- edit_device.php
- edit_main.php
- edit_penjualan.php
- edit_price.php
- edit_reseller.php
- edit_servers.php
- edit_serverstatus.php
- edit_userinfo.php
- export_customers.php
- history_refil.php
- index.php
- jsonout.php
- k.php
- login.php
- logout.php
- paid_refil.php
- price.php
- servers.php
- serverspatch.php
- serverstatus.php
- setpatch.php
- setserver.php
- soldrs_DHRU.php
- test.php

## Result

✅ No more session warnings
✅ Session isolation from dashboard still works
✅ Unique session name: `RESELLER_SESSION`
✅ Cookies still scoped to `/reseller`

## Verification

Test any reseller page - no warnings:
- http://localhost:8080/reseller/login.php
- http://localhost:8080/reseller/index.php
- etc.
