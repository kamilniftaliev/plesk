# Telegram OTP Setup Guide

This guide explains how to set up Telegram OTP authentication for your dashboard.

## Prerequisites

- A Telegram account
- Basic understanding of Telegram bots

## Step 1: Create a Telegram Bot

1. Open Telegram and search for **@BotFather**
2. Start a chat with BotFather and send: `/newbot`
3. Follow the prompts:
   - Choose a name for your bot (e.g., "My Dashboard OTP Bot")
   - Choose a username (must end in 'bot', e.g., "mydashboard_otp_bot")
4. BotFather will provide you with a **Bot Token** (looks like: `123456789:ABCdefGHIjklMNOpqrsTUVwxyz`)
5. **Save this token** - you'll need it for configuration

## Step 2: Configure the Dashboard

1. Open `/home/user/Desktop/plesk/dashboard/config/config.php`
2. Find the Telegram configuration section
3. Update these values:
   ```php
   define('TELEGRAM_BOT_TOKEN', '123456789:ABCdefGHIjklMNOpqrsTUVwxyz'); // Your bot token
   define('TELEGRAM_BOT_ENABLED', true); // Enable Telegram OTP
   ```

## Step 3: Add Database Column

Run the SQL migration file to add the telegram_chat_id column:

```bash
mysql -u your_username -p your_database < /home/user/Desktop/plesk/dashboard/migrations/add_telegram_chat_id.sql
```

Or execute this SQL manually in phpMyAdmin:

```sql
ALTER TABLE `user` ADD COLUMN `telegram_chat_id` VARCHAR(255) NULL DEFAULT NULL AFTER `email`;
ALTER TABLE `user` ADD INDEX `idx_telegram_chat_id` (`telegram_chat_id`);
```

## Step 4: Get Your Telegram Chat ID

You need your Telegram Chat ID to receive OTP codes. Here are several methods:

### Method 1: Using @userinfobot
1. Search for **@userinfobot** in Telegram
2. Start the bot and send any message
3. It will reply with your User ID (this is your Chat ID)
4. Example response: `Id: 123456789`

### Method 2: Using @RawDataBot
1. Search for **@RawDataBot** in Telegram
2. Start the bot and send any message
3. Look for the `"id"` field in the JSON response
4. Example: `"from": { "id": 123456789, ... }`

### Method 3: Using Your Bot Directly
1. Start a chat with your newly created bot (username you chose in Step 1)
2. Send any message to the bot (e.g., "Hello")
3. Open this URL in your browser (replace with your bot token):
   ```
   https://api.telegram.org/bot123456789:ABCdefGHIjklMNOpqrsTUVwxyz/getUpdates
   ```
4. Look for `"chat": { "id": 123456789 }` in the response

## Step 5: Add Chat ID to User Account

Update your user record in the database with your Telegram Chat ID:

```sql
UPDATE `user` SET `telegram_chat_id` = '123456789' WHERE `username` = 'your_username';
```

Or use phpMyAdmin:
1. Navigate to the `user` table
2. Find your user record
3. Edit the `telegram_chat_id` field
4. Enter your Chat ID (e.g., `123456789`)
5. Save

## Step 6: Test the Setup

1. **Start Development Mode Test**:
   - Make sure `DEV_MODE` is set to `true` in config.php
   - Try logging in
   - You should see the OTP code displayed on screen
   - Verify it shows both Email and Telegram delivery info

2. **Enable Production Mode**:
   - Set `DEV_MODE` to `false` in config.php
   - Make sure `TELEGRAM_BOT_ENABLED` is `true`
   - Try logging in
   - Check your Telegram app for the OTP message

## Expected Telegram Message Format

When you log in, you'll receive a message like this:

```
🔐 Login Verification Code

Hello YourUsername,

Your verification code is:

🔢 1234

⏰ This code will expire in 10 minutes
🔒 Do not share this code with anyone

⚠️ If you did not request this code, please ignore this message.
```

## Troubleshooting

### Bot not sending messages?
- **Check Bot Token**: Ensure it's correct in config.php
- **Check TELEGRAM_BOT_ENABLED**: Must be `true`
- **Start the Bot**: You must send at least one message to your bot before it can message you
- **Check Chat ID**: Ensure it's correct in the database
- **Test Bot**: Try sending a test message using this URL:
  ```
  https://api.telegram.org/bot<YOUR_BOT_TOKEN>/sendMessage?chat_id=<YOUR_CHAT_ID>&text=Test
  ```

### "No email or Telegram configured" error?
- Make sure you have either an email OR a telegram_chat_id in your user record
- Both can be configured for dual delivery

### OTP not received?
- Check if the bot is blocked by you on Telegram
- Verify the chat_id is correct
- Check Telegram's "Archived Chats" or "Spam" folder
- Ensure your server can make HTTPS requests to Telegram's API

## Dual Delivery: Email + Telegram

You can configure both email and Telegram for maximum reliability:
1. Add your email address to the `email` field
2. Add your chat_id to the `telegram_chat_id` field
3. When you log in, OTP will be sent to BOTH email and Telegram

## Security Notes

- **Never share your bot token** - treat it like a password
- **Never share your Chat ID** publicly
- **Keep your bot private** - don't add it to groups unless necessary
- **Regularly rotate your bot token** if compromised
- The OTP code expires after 10 minutes for security

## Additional Configuration

### Customize Bot Messages
Edit the `sendTelegramOTP()` function in authenticate.php to customize the message format.

### Disable Email, Use Only Telegram
1. Remove email addresses from user records
2. Add telegram_chat_id to all users
3. System will automatically use Telegram only

### Use Both Methods
Configure both email and telegram_chat_id for redundancy.

## Support

If you encounter issues:
1. Check the configuration in config.php
2. Verify database column exists
3. Test with DEV_MODE first
4. Check server logs for errors
5. Verify bot token and chat_id are correct
