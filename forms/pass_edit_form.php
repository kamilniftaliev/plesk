<fieldset class="flex justify-center flex-col">
    <!-- Error Box -->
    <div id="validation-error-box" class="bg-red-700 p-4 text-white border border-red-900"
        style="display: none; margin-bottom: 20px; border-radius: 4px;">
        <strong class="text-3xl">Error:</strong>
        <ul id="validation-error-list" style="margin: 10px 0 0 0; padding-left: 20px;"></ul>
    </div>

    <div class="form-group">
        <label class="col-md-4 control-label">Enter Old Password</label>
        <div class="col-md-5 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input type="password" name="oldpassword" autocomplete="off" placeholder="Old Password"
                    class="form-control" required="" autocomplete="off">
            </div>
        </div>
    </div>

    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label">Enter New Password</label>
        <div class="col-md-5 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input type="password" name="newpassword" id="newpassword" autocomplete="off" placeholder="New Password"
                    class="form-control" required="" autocomplete="off">
            </div>
            <small class="text-right" style="color: #666; margin-top: 5px; display: block;">
                Password must be 8-64 characters and contain both letters and numbers
            </small>
        </div>
    </div>

    <!-- Confirm New Password -->
    <div class="form-group">
        <label class="col-md-4 control-label">Confirm New Password</label>
        <div class="col-md-5 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input type="password" name="confirmpassword" id="confirmpassword" autocomplete="off"
                    placeholder="Confirm New Password" class="form-control" required="" autocomplete="off">
            </div>
            <small id="password-match-message" style="display: none; margin-top: 5px;"></small>
        </div>
    </div>

    <!-- Button -->
    <div class="form-group">
        <div class="col-md-12 flex justify-center">
            <button type="submit" class="btn btn-warning" id="submit-btn">Save <span
                    class="glyphicon glyphicon-send"></span></button>
        </div>
    </div>
</fieldset>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const newPassword = document.getElementById('newpassword');
        const confirmPassword = document.getElementById('confirmpassword');
        const message = document.getElementById('password-match-message');
        const submitBtn = document.getElementById('submit-btn');
        const form = document.getElementById('contact_form');
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

            // Scroll to error box
            errorBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        function hideErrorBox() {
            errorBox.style.display = 'none';
            errorList.innerHTML = '';
        }

        function validatePasswordStrength(password) {
            const errors = [];

            // Check length
            if (password.length < 8) {
                errors.push('Password must have at least 8 characters');
            }
            if (password.length > 64) {
                errors.push('Password must have no more than 64 characters');
            }

            // Check for letters
            if (!/[a-zA-Z]/.test(password)) {
                errors.push('Password must have at least one letter');
            }

            // Check for numbers
            if (!/[0-9]/.test(password)) {
                errors.push('Password must have at least one number');
            }

            return errors;
        }

        function checkPasswordMatch() {
            const password = newPassword.value;
            const confirm = confirmPassword.value;

            // Hide error box when user is typing
            hideErrorBox();

            // First, validate password strength
            if (password !== '') {
                const strengthErrors = validatePasswordStrength(password);

                if (strengthErrors.length > 0) {
                    message.textContent = '✗ Password must have: ' + strengthErrors.map(e => e.replace('Password must have ', '')).join(', ');
                    message.style.color = '#dc3545';
                    message.style.display = 'block';
                    return false;
                }
            }

            // Then check if passwords match
            if (confirm === '') {
                if (password !== '') {
                    message.textContent = '✓ Password strength OK';
                    message.style.color = '#28a745';
                    message.style.display = 'block';
                } else {
                    message.style.display = 'none';
                }
                return;
            }

            if (password === confirm) {
                message.textContent = '✓ Passwords match and meet requirements';
                message.style.color = '#28a745';
                message.style.display = 'block';
                return true;
            } else {
                message.textContent = '✗ Passwords do not match';
                message.style.color = '#dc3545';
                message.style.display = 'block';
                return false;
            }
        }

        // Check on input
        newPassword.addEventListener('input', checkPasswordMatch);
        confirmPassword.addEventListener('input', checkPasswordMatch);

        // Prevent form submission if validation fails
        form.addEventListener('submit', function (e) {
            const password = newPassword.value;
            const confirm = confirmPassword.value;
            const errors = [];

            // Check password strength
            const strengthErrors = validatePasswordStrength(password);
            if (strengthErrors.length > 0) {
                e.preventDefault();
                errors.push(...strengthErrors);
            }

            // Check if passwords match
            if (confirm !== '' && password !== confirm) {
                e.preventDefault();
                errors.push('Passwords do not match. Please make sure both password fields are identical');
            }

            // Show errors in red box if any
            if (errors.length > 0) {
                showErrorBox(errors);
                if (strengthErrors.length > 0) {
                    newPassword.focus();
                } else {
                    confirmPassword.focus();
                }
                return;
            }
        });
    });
</script>