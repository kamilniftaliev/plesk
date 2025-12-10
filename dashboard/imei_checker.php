<?php
session_name('DASHBOARD_SESSION');
session_start();
require_once './config/config.php';
require_once 'includes/auth_validate.php';

// Function to check activation lock status
function checkActivationLockStatus($imei)
{
    $ts = round(microtime(true) * 1000);
    $url = "https://i.mi.com/support/anonymous/status?ts=$ts&id=$imei";
    $cookie = "xm_user_bucket=2; uLocale=en_US; iplocale=en; i.mi.com_isvalid_servicetoken=false; i.mi.com_istrudev=false;";

    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Host: i.mi.com",
            "Connection: keep-alive",
            "sec-ch-ua-platform: \"Windows\"",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36",
            "sec-ch-ua: \"Not(A:Brand\";v=\"99\", \"Google Chrome\";v=\"133\", \"Chromium\";v=\"133\"",
            "sec-ch-ua-mobile: ?0",
            "Accept: */*",
            "Sec-Fetch-Site: same-origin",
            "Sec-Fetch-Mode: cors",
            "Sec-Fetch-Dest: empty",
            "Referer: https://i.mi.com/find/device/full/status",
            "Accept-Encoding: gzip, deflate, br, zstd",
            "Accept-Language: en-US,en;q=0.9",
            "Cookie: $cookie"
        ]);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            return ['success' => false, 'error' => $error];
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return ['success' => false, 'error' => "HTTP $httpCode"];
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['success' => false, 'error' => "JSON parse error"];
        }

        return ['success' => true, 'data' => $data];
    } else {
        // Fallback to file_get_contents
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => "Host: i.mi.com\r\n" .
                    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36\r\n" .
                    "Accept: */*\r\n" .
                    "Cookie: $cookie\r\n",
                'timeout' => 10,
                'ignore_errors' => true
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false
            ]
        ]);

        $response = @file_get_contents($url, false, $context);
        if ($response === false) {
            return ['success' => false, 'error' => 'Failed to connect to MI server'];
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['success' => false, 'error' => "JSON parse error"];
        }

        return ['success' => true, 'data' => $data];
    }
}

// Handle AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

    $imei = filter_input(INPUT_POST, 'imei', FILTER_SANITIZE_SPECIAL_CHARS);
    $vcode = filter_input(INPUT_POST, 'vcode', FILTER_SANITIZE_SPECIAL_CHARS);

    // Validate IMEI
    if (empty($imei) || !preg_match('/^\d{15}$/', $imei)) {
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Invalid IMEI. Must be exactly 15 digits.'
            ]);
        } else {
            $_SESSION['failure'] = 'Invalid IMEI. Must be exactly 15 digits.';
        }
        exit;
    }

    // Validate verification code
    if (empty($vcode)) {
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Verification code is required.'
            ]);
        } else {
            $_SESSION['failure'] = 'Verification code is required.';
        }
        exit;
    }

    // Build URL
    // $url = "https://buy.mi.com/en/other/checkimei?jsonpcallback=JSON_CALLBACK&keyword=" .
    //     urlencode($imei) . "&vcode=" . urlencode($vcode) . "&v=" .
    //     mt_rand() / mt_getrandmax() . "&_=1757774608713";
    $url = "https://buy.mi.com/en/other/checkimei?jsonpcallback=JSON_CALLBACK&keyword=" .
        urlencode($imei) . "&vcode=" . urlencode($vcode) . "&v=1234&_=1757774608713";

    // Check if cURL is available, otherwise use file_get_contents
    if (function_exists('curl_init')) {
        // Initialize cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        // Headers
        $headers = [
            "Connection: keep-alive",
            "sec-ch-ua: \"Not(A:Brand\";v=\"99\", \"Google Chrome\";v=\"133\", \"Chromium\";v=\"133\"",
            "sec-ch-ua-mobile: ?0",
            "sec-ch-ua-platform: \"Windows\"",
            "Upgrade-Insecure-Requests: 1",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36",
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7",
            "Sec-Fetch-Site: none",
            "Sec-Fetch-Mode: navigate",
            "Sec-Fetch-User: ?1",
            "Sec-Fetch-Dest: document",
            "Accept-Language: en-US,en;q=0.9",
            "Accept-Encoding: gzip, deflate"
        ];

        // cURL options
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_ENCODING => "",
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 20,
        ]);

        // Use the same cookie file that was created when fetching captcha
        if (isset($_SESSION['mi_cookie_file']) && file_exists($_SESSION['mi_cookie_file'])) {
            curl_setopt($ch, CURLOPT_COOKIEFILE, $_SESSION['mi_cookie_file']);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $_SESSION['mi_cookie_file']);
        }

        // Execute request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            $err = curl_error($ch);
            curl_close($ch);

            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Connection Error: ' . $err
                ]);
            } else {
                $_SESSION['failure'] = 'Connection Error: ' . $err;
            }
            exit;
        }

        curl_close($ch);
    } else {
        // Fallback to file_get_contents
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36\r\n",
                'timeout' => 20,
                'ignore_errors' => true
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false
            ]
        ]);

        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to connect to MI server'
                ]);
            } else {
                $_SESSION['failure'] = 'Failed to connect to MI server';
            }
            exit;
        }
    }

    // Process response
    if ($response && preg_match('/JSON_CALLBACK\((.*)\);/', $response, $m)) {
        $json = json_decode($m[1], true);

        if ($json && isset($json['data'])) {
            $goods = htmlspecialchars($json['data']['goods_name']);
            $country = htmlspecialchars($json['data']['country_text']);
            $time = $json['data']['add_time'];

            // Check activation lock status
            $activationLockStatus = checkActivationLockStatus($imei);

            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'data' => [
                        'goods_name' => $goods,
                        'country_text' => $country,
                        'add_time' => date("Y-m-d H:i:s", $time),
                        'timestamp' => $time,
                        'activation_lock' => $activationLockStatus
                    ]
                ]);
            } else {
                $_SESSION['success'] = "Device: $goods | Country: $country | Activation: " . date("Y-m-d H:i:s", $time);
            }
        } else {
            $error = $json['msg'] ?? "Invalid response data from server";
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => $error
                ]);
            } else {
                $_SESSION['failure'] = $error;
            }
        }
    } else {
        $error = "No valid response from server";
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => $error
            ]);
        } else {
            $_SESSION['failure'] = $error;
        }
    }

    if (!$isAjax) {
        header('Location: imei_checker.php');
    }
    exit;
}

// Load appropriate header based on user type
if ($_SESSION['admin_type'] == 'user') {
    require_once 'includes/user_header.php';
}
if ($_SESSION['admin_type'] == 'admin') {
    require_once 'includes/admin_header.php';
}
if ($_SESSION['admin_type'] == 'reseller') {
    require_once 'includes/reseller_header.php';
}

?>

<style>
    .captcha-image {
        cursor: pointer;
        border: 1px solid #ccc;
        border-radius: 4px;
        height: 50px;
        width: auto;
    }

    #captcha-status i {
        color: #fff;
        font-size: 16px;
    }

    #captcha-status span {
        color: #fff;
        font-size: 14px;
        font-weight: normal;
    }

    #manual-captcha-container {
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        padding: 15px;
        background: #f9f9f9;
    }

    .info-icon {
        cursor: pointer;
        color: #5bc0de;
        margin-left: 5px;
        font-size: 16px;
    }

    .info-icon:hover {
        color: #46b8da;
    }

    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 10% auto;
        padding: 0;
        border: 1px solid #888;
        width: 90%;
        max-width: 500px;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        padding: 15px;
        background-color: #5bc0de;
        color: white;
        border-radius: 5px 5px 0 0;
    }

    .modal-body {
        padding: 20px;
    }

    .close {
        color: white;
        float: right;
        font-size: 28px;
        font-weight: bold;
        line-height: 20px;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: #f1f1f1;
    }
</style>

<div id="page-wrapper">

    <div class="row">
        <h1 class="page-header mb-4">Xiaomi IMEI Checker</h1>
        <p class="text-muted mb-4">Check Xiaomi device information using IMEI number</p>
    </div>

    <?php include_once 'includes/flash_messages.php'; ?>

    <div class="flex justify-center w-full">
        <div class="col-lg-8 px-0">
            <div class="panel panel-default">
                <div class="panel-heading flex items-center gap-3">
                    <i class="fa fa-mobile text-4xl w-4 fa-fw"></i>
                    <span>Check IMEI</span>
                </div>
                <div class="flex flex-col gap-6 p-6">
                    <form id="imei-form" method="POST" action="" class="flex flex-col gap-7">
                        <div class="gap-2 flex flex-col">
                            <label for="imei" class="mb-0">
                                IMEI Number
                                <i class="fa fa-question-circle info-icon" id="info-icon"></i>
                            </label>
                            <input type="text" class="form-control" id="imei" value="" name="imei"
                                placeholder="Enter 15-digit IMEI" maxlength="15" required>
                            <small class="text-muted">Enter your 15-digit IMEI number</small>
                        </div>

                        <!-- Hidden captcha image for background processing -->
                        <img id="captcha-image" src="" style="display: none;" alt="Verification Code">
                        <input type="hidden" id="vcode" name="vcode" required>

                        <!-- Captcha solving status -->
                        <div id="captcha-status" class="flex items-center justify-center gap-2 p-2"
                            style="display: none;">
                            <i class="fa fa-spinner fa-spin"></i>
                            <span>Solving captcha<span id="loading-dots">...</span></span>
                        </div>

                        <!-- Manual captcha container (shown if auto-solve fails) -->
                        <div id="manual-captcha-container" style="display: none;">
                            <label class="text-sm text-gray-700 mb-2 block">Automatic solving failed. Please enter the
                                code manually:</label>
                            <div class="flex gap-3 items-center justify-center mb-3">
                                <img id="manual-captcha-image" src="" class="captcha-image" alt="Verification Code"
                                    style="display: inline-block; height: 40px;">
                                <button type="button" id="refresh-manual-captcha" class="btn btn-sm btn-default">
                                    <i class="fa fa-refresh"></i>
                                </button>
                            </div>
                            <input type="text" class="form-control" id="manual-vcode"
                                placeholder="Enter verification code">
                        </div>

                        <div class="flex gap-4 items-center justify-center">
                            <button type="submit" id="submit-btn" class="btn btn-primary">
                                <i class="fa fa-search"></i> Check IMEI
                            </button>
                            <button type="button" id="clear-btn" class="btn btn-default">
                                <i class="fa fa-times"></i> Clear
                            </button>
                        </div>
                    </form>


                    <div id="result-box" style="display: none;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Information Modal -->
    <div id="info-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close">&times;</span>
                <h4 style="margin: 0;">How to use Xiaomi IMEI Checker</h4>
            </div>
            <div class="modal-body">
                <ol>
                    <li>Enter your 15-digit IMEI number</li>
                    <li>Captcha will be solved automatically in the background</li>
                    <li>Click "Check IMEI" to verify (button will be enabled when ready)</li>
                </ol>
                <hr>
                <h4>How to find IMEI:</h4>
                <ul>
                    <li>Dial <strong>*#06#</strong> on your phone</li>
                    <li>Check Settings → About Phone → Status</li>
                    <li>Check the box or back of your device</li>
                </ul>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('imei-form');
        const imeiInput = document.getElementById('imei');
        const vcodeInput = document.getElementById('vcode');
        const submitBtn = document.getElementById('submit-btn');
        const clearBtn = document.getElementById('clear-btn');
        const resultBox = document.getElementById('result-box');
        const captchaImage = document.getElementById('captcha-image');
        const manualCaptchaContainer = document.getElementById('manual-captcha-container');
        const manualCaptchaImage = document.getElementById('manual-captcha-image');
        const manualVcodeInput = document.getElementById('manual-vcode');
        const refreshManualCaptcha = document.getElementById('refresh-manual-captcha');

        // Variable to store the loading animation interval
        let dotsInterval;
        let captchaSolved = false;
        let manualMode = false;

        // Modal elements
        const modal = document.getElementById('info-modal');
        const infoIcon = document.getElementById('info-icon');

        // Initial button states
        submitBtn.disabled = true; // Disabled until IMEI has 15 digits AND captcha is solved
        clearBtn.disabled = true; // Disabled until IMEI field has content

        // Modal functionality
        infoIcon.addEventListener('click', function (e) {
            e.preventDefault();
            modal.style.display = 'block';
        });

        // Close modal when clicking the X button
        modal.addEventListener('click', function (event) {
            if (event.target.classList.contains('close')) {
                modal.style.display = 'none';
            }
            // Close when clicking outside the modal content
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });

        // Load captcha on page load
        loadCaptcha();

        function loadCaptcha() {
            // Only load and solve captcha if it hasn't been solved yet
            if (captchaSolved) {
                return;
            }

            const timestamp = new Date().getTime();
            const random = Math.random();
            const captchaStatus = document.getElementById('captcha-status');

            // Show loading status and disable submit button
            captchaStatus.style.display = 'flex';
            submitBtn.disabled = true;

            // Start animated dots
            startLoadingAnimation();

            captchaImage.src = 'get_captcha.php?v=' + random + '&t=' + timestamp;
            captchaImage.onload = function () {
                // Automatically solve captcha using 2captcha
                solveCaptcha();
            };
        }

        function startLoadingAnimation() {
            const dotsElement = document.getElementById('loading-dots');
            let dots = 0;

            dotsInterval = setInterval(() => {
                dots = (dots + 1) % 4;
                dotsElement.textContent = '.'.repeat(dots || 1);
            }, 500);
        }

        function stopLoadingAnimation() {
            if (dotsInterval) {
                clearInterval(dotsInterval);
            }
        }

        function showManualCaptcha() {
            manualMode = true;
            manualCaptchaContainer.style.display = 'block';
            manualCaptchaImage.src = captchaImage.src;
            // Only enable submit button if IMEI has 15 digits
            submitBtn.disabled = imeiInput.value.length !== 15;
        }

        // Manual captcha refresh
        refreshManualCaptcha.addEventListener('click', function () {
            const timestamp = new Date().getTime();
            const random = Math.random();
            const newSrc = 'get_captcha.php?v=' + random + '&t=' + timestamp;
            captchaImage.src = newSrc;
            manualCaptchaImage.src = newSrc;
            manualVcodeInput.value = '';
        });

        // Update hidden input when manual code is entered
        manualVcodeInput.addEventListener('input', function () {
            vcodeInput.value = this.value;
            if (this.value.trim() !== '') {
                captchaSolved = true;
                // Enable submit button only if IMEI has 15 digits
                if (imeiInput.value.length === 15) {
                    submitBtn.disabled = false;
                }
            } else {
                submitBtn.disabled = true;
            }
        });

        async function solveCaptcha() {
            try {
                // Step 1: Convert the already-rendered captcha image to blob
                const canvas = document.createElement('canvas');
                canvas.width = captchaImage.naturalWidth || captchaImage.width;
                canvas.height = captchaImage.naturalHeight || captchaImage.height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(captchaImage, 0, 0);

                const imageBlob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', 0.95));
                // console.log('Captcha image blob created from rendered image:', imageBlob);

                // Step 2: Submit captcha to 2captcha via our backend
                const formData = new FormData();
                formData.append('action', 'submit');
                formData.append('captcha_image', imageBlob, 'captcha.jpg');

                const submitResponse = await fetch('captcha_solver.php', {
                    method: 'POST',
                    body: formData
                });

                const submitResult = await submitResponse.json();
                // console.log('Submit result:', submitResult);

                if (!submitResult.success) {
                    console.error('Failed to submit captcha:', submitResult.error);
                    stopLoadingAnimation();
                    document.getElementById('captcha-status').style.display = 'none';
                    showManualCaptcha();
                    return;
                }

                const captchaId = submitResult.captcha_id;
                // console.log('Captcha ID:', captchaId);

                // Step 3: Wait 10 seconds
                // console.log('Waiting 10 seconds for captcha to be solved...');
                await new Promise(resolve => setTimeout(resolve, 10000));

                // Step 4: Poll for result (try up to 5 times with 10 second intervals)
                let attempts = 0;
                const maxAttempts = 5;

                while (attempts < maxAttempts) {
                    // console.log(`Attempt ${attempts + 1} to get captcha result...`);

                    const resultResponse = await fetch(`captcha_solver.php?action=get&captcha_id=${captchaId}`);
                    const resultData = await resultResponse.json();
                    // console.log('Result data:', resultData);

                    if (resultData.success && resultData.code) {
                        // Success! Fill in the verification code
                        vcodeInput.value = resultData.code;
                        // console.log('Captcha solved:', resultData.code);

                        // Mark captcha as solved
                        captchaSolved = true;

                        // Hide loading status
                        stopLoadingAnimation();
                        document.getElementById('captcha-status').style.display = 'none';

                        // Enable submit button only if IMEI has 15 digits
                        if (imeiInput.value.length === 15) {
                            submitBtn.disabled = false;
                        }
                        return;
                    } else if (resultData.pending) {
                        // Still processing, wait and retry
                        // console.log('Captcha not ready yet, waiting 10 seconds...');
                        await new Promise(resolve => setTimeout(resolve, 10000));
                        attempts++;
                    } else {
                        // Error
                        console.error('Failed to get captcha result:', resultData.error);
                        stopLoadingAnimation();
                        document.getElementById('captcha-status').style.display = 'none';
                        showManualCaptcha();
                        return;
                    }
                }

                // Timeout - show manual captcha
                console.error('Captcha solving timeout after', maxAttempts, 'attempts');
                stopLoadingAnimation();
                document.getElementById('captcha-status').style.display = 'none';
                showManualCaptcha();
            } catch (error) {
                console.error('Error solving captcha:', error);
                stopLoadingAnimation();
                document.getElementById('captcha-status').style.display = 'none';
                showManualCaptcha();
            }
        }

        // Only allow numbers in IMEI field and handle button states
        imeiInput.addEventListener('input', function (e) {
            this.value = this.value.replace(/[^0-9]/g, '');

            // Enable/disable submit button based on IMEI length and captcha status
            if (this.value.length === 15 && captchaSolved) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }

            // Enable/disable clear button based on field content
            clearBtn.disabled = this.value.length === 0;
        });

        // Clear button
        clearBtn.addEventListener('click', function () {
            // Only clear the IMEI input and result box
            imeiInput.value = '';
            resultBox.style.display = 'none';

            // Update button states
            submitBtn.disabled = !captchaSolved;
            clearBtn.disabled = true;
        });

        // Form submission
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const imei = imeiInput.value.trim();
            const vcode = vcodeInput.value.trim();

            // Validate IMEI
            if (imei.length !== 15) {
                showResult('error', 'IMEI must be exactly 15 digits');
                return;
            }

            // Validate vcode (should be automatically filled by captcha solver)
            if (vcode === '') {
                showResult('error', 'Captcha not solved yet. Please wait.');
                return;
            }

            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Checking...';

            // Send AJAX request
            const formData = new FormData();
            formData.append('imei', imei);
            formData.append('vcode', vcode);

            fetch('imei_checker.php', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Parse activation lock status
                        let findMyDeviceStatus = 'Unknown';
                        let findMyDeviceColor = '#666';
                        let activationData = data.data?.activation_lock?.data;

                        if (data.data.activation_lock && data.data.activation_lock.success && data.data.activation_lock.data) {
                            const lockData = data.data.activation_lock.data;
                            if (lockData.data) {
                                if (lockData.code === 0) {
                                    findMyDeviceStatus = 'OFF';
                                    findMyDeviceColor = '#28a745'; // Green
                                } else if (lockData.code === 1) {
                                    findMyDeviceStatus = 'ON';
                                    findMyDeviceColor = '#dc3545'; // Red
                                } else {
                                    findMyDeviceStatus = 'Status: ' + lockData.data.status;
                                    findMyDeviceColor = '#ffc107'; // Yellow
                                }
                            }
                        } else if (data.data.activation_lock && !data.data.activation_lock.success) {
                            findMyDeviceStatus = 'Error checking status';
                            findMyDeviceColor = '#666';
                        }

                        const resultHTML = `
                    <strong>Device Information:</strong>
                    <table class="table table-bordered" style="margin-top: 20px;font-size: 14px;">
                        <tr>
                            <td><strong>Device:</strong></td>
                            <td>${data.data.goods_name}</td>
                        </tr>
                        <tr>
                            <td><strong>Country:</strong></td>
                            <td>${data.data.country_text}</td>
                        </tr>
                        <tr>
                            <td><strong>Activation Time:</strong></td>
                            <td>${data.data.add_time}</td>
                        </tr>
                        <tr>
                            <td><strong>Device Status:</strong></td>
                            <td><strong style="color: ${findMyDeviceColor}; font-size: 16px;">${findMyDeviceStatus}</strong></td>
                        </tr>
                    </table>
                `;
                        showResult('success', resultHTML);
                    } else {
                        showResult('error', data.message);
                        // Don't reload captcha, keep the solved one
                    }
                })
                .catch(error => {
                    showResult('error', 'Network error: ' + error.message);
                    // Don't reload captcha, keep the solved one
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fa fa-search"></i> Check IMEI';
                });
        });

        function showResult(type, message) {
            if (type === 'success') {
                // resultBox.className = 'alert alert-success';
                resultBox.innerHTML = '<i class="fa fa-check-circle"></i> ' + message;
            } else {
                resultBox.className = 'alert alert-danger';
                resultBox.innerHTML = '<i class="fa fa-exclamation-circle"></i> ' + message;
            }
            resultBox.style.display = 'block';
            resultBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    });
</script>

<?php include_once 'includes/footer.php'; ?>