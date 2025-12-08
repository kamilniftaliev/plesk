# Authid Login Information

## Access URL
**http://localhost:8080/authid/login.php**

## Authentication Requirements

The authid login requires **THREE** pieces of information:

1. **Username** - Your user account username
2. **Password** - Your user account password
3. **Hour Password** - The current hour in 24-hour format (00-23)

## Hour Password Rules

- The hour password must match the **current hour** when you submit the form
- Format: Two digits, 24-hour format (00-23)
- Examples:
  - If it's 2:30 PM → Enter: `14`
  - If it's 9:45 AM → Enter: `09`
  - If it's 11:15 PM → Enter: `23`
  - If it's 12:30 AM → Enter: `00`

## How to Get Current Hour

Run this command to see the current hour:
```bash
date +%H
```

Or in PHP:
```php
echo date('H');
```

## Login Example

If the current time is **3:45 PM (15:45)**:
- Username: `admin` (or your username)
- Password: `your_password`
- Hour Password: `15`

## Error Messages

- **"Invalid hour password. Please enter the current hour (00-23)."**
  → The hour you entered doesn't match the current server hour

- **"Invalid user name or password"**
  → Your username or password is incorrect

## Notes

- The hour password changes every hour automatically
- Make sure your system time is correct
- The hour is checked on the server side using PHP's `date('H')` function
