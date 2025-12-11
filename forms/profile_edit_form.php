<fieldset class="flex justify-center flex-col">
    <!-- Success/Error Box -->
    <div id="validation-error-box" class="bg-red-700 p-4 text-white border border-red-900"
        style="display: none; margin-bottom: 20px; border-radius: 4px;">
        <strong class="text-3xl">Error:</strong>
        <ul id="validation-error-list" style="margin: 10px 0 0 0; padding-left: 20px;"></ul>
    </div>

    <!-- Username -->
    <div class="form-group">
        <label class="col-md-4 control-label">Username</label>
        <div class="col-md-5 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input type="text" name="username" id="username" autocomplete="off" placeholder="Username"
                    class="form-control" required="" value="<?php echo htmlspecialchars($admin_account['username']); ?>">
            </div>
            <small style="color: #666; margin-top: 5px; display: block;">
                6-30 characters, letters only (no numbers or special characters)
            </small>
        </div>
    </div>

    <!-- Email -->
    <div class="form-group">
        <label class="col-md-4 control-label">Email Address</label>
        <div class="col-md-5 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                <input type="email" name="email" id="email" autocomplete="off" placeholder="Email Address"
                    class="form-control" required="" value="<?php echo htmlspecialchars($admin_account['email']); ?>">
            </div>
            <small style="color: #666; margin-top: 5px; display: block;">
                Used for OTP verification and notifications
            </small>
        </div>
    </div>

    <!-- Telegram Chat ID -->
    <div class="form-group">
        <label class="col-md-4 control-label">Telegram Chat ID</label>
        <div class="col-md-5 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-telegram"></i></span>
                <input type="text" name="telegram_chat_id" id="telegram_chat_id" autocomplete="off"
                    placeholder="Your Telegram Chat ID (optional)" class="form-control"
                    value="<?php echo htmlspecialchars($admin_account['telegram_chat_id'] ?? ''); ?>">
            </div>
            <small style="color: #666; margin-top: 5px; display: block;">
                Optional: Must be exactly 10 digits (for receiving OTP codes via Telegram)
            </small>
        </div>
    </div>

    <!-- Button -->
    <div class="form-group">
        <div class="col-md-12 flex justify-center">
            <button type="submit" class="btn btn-primary" id="submit-btn">
                Update Profile <span class="glyphicon glyphicon-save"></span>
            </button>
        </div>
    </div>
</fieldset>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const username = document.getElementById('username');
        const email = document.getElementById('email');
        const telegramChatId = document.getElementById('telegram_chat_id');
        const form = document.getElementById('profile_form');
        const errorBox = document.getElementById('validation-error-box');
        const errorList = document.getElementById('validation-error-list');

        function showErrorBox(errors) {
            errorList.innerHTML = '';
            errors.forEach(function (error) {
                const li = document.createElement('li');
                li.textContent = error;
                errorList.appendChild(li);
            });
            errorBox.style.display = 'block';
            errorBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        function hideErrorBox() {
            errorBox.style.display = 'none';
            errorList.innerHTML = '';
        }

        // Hide error box when user types
        username.addEventListener('input', hideErrorBox);
        email.addEventListener('input', hideErrorBox);
        telegramChatId.addEventListener('input', hideErrorBox);

        // Form validation
        form.addEventListener('submit', function (e) {
            const errors = [];

            // Validate username - must be letters only
            if (username.value.trim().length < 6) {
                errors.push('Username must be at least 6 characters long');
            }
            if (username.value.trim().length > 30) {
                errors.push('Username must not exceed 30 characters');
            }
            if (!/^[a-zA-Z]+$/.test(username.value.trim())) {
                errors.push('Username must contain only letters (no numbers, spaces, or special characters)');
            }

            // Validate email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value)) {
                errors.push('Please enter a valid email address');
            }

            // Validate Telegram Chat ID (optional, but if provided must be exactly 10 digits)
            if (telegramChatId.value.trim() !== '') {
                if (!/^\d{10}$/.test(telegramChatId.value.trim())) {
                    errors.push('Telegram Chat ID must be exactly 10 digits');
                }
            }

            // Show errors if any
            if (errors.length > 0) {
                e.preventDefault();
                showErrorBox(errors);
                return;
            }
        });
    });
</script>
